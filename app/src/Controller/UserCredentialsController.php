<?php
/**
 * User credentials controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserEmailType;
use App\Form\Type\UserPasswordType;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserCredentialsController.
 */
#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserCredentialsController extends AbstractController
{
    /**
     * UserService.
     */
    private UserService $userService;

    /**
     * Security.
     */
    private Security $security;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * UserPasswordHasherInterface.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserCredentialsController constructor.
     *
     * @param UserService                 $userService    User service
     * @param Security                    $security       Security
     * @param TranslatorInterface         $translator     Translator
     * @param UserPasswordHasherInterface $passwordHasher Password Hasher
     */
    public function __construct(UserService $userService, Security $security, TranslatorInterface $translator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userService = $userService;
        $this->security = $security;
        $this->translator = $translator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Change logged-in user's email.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(path: '/change_email', name: 'user_email_edit')]
    public function changeEmail(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $form = $this->createForm(
            UserEmailType::class,
            $user,
            ['action' => $this->generateUrl('user_email_edit')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($form->get('email')->getData());
            $this->userService->save($user);
            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('task_index');
        }

        return $this->render(
            'change_user_credentials/email.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Change logged-in user's password.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(path: '/change_password', name: 'user_password_edit')]
    public function changePassword(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $form = $this->createForm(
            UserPasswordType::class,
            $user,
            ['action' => $this->generateUrl('user_password_edit')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('password')->getData();
            $newPassword = $form->get('new_password')->getData();
            if (0 === strcmp($user->getPassword(), $oldPassword)) {
                $user->setPassword(
                    $this->passwordHasher->hashPassword($user, $newPassword)
                );
                $this->userService->save($user);
                $this->addFlash(
                    'success',
                    $this->translator->trans('message.edited_successfully')
                );
            } else {
                $this->addFlash(
                    'warning',
                    $this->translator->trans('message.invalid_password')
                );

                return $this->redirectToRoute('task_index');
            }
        }

        return $this->render(
            'change_user_credentials/password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
