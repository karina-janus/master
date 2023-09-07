<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Service\CategoryServiceInterface;
use App\Service\NoteServiceInterface;
use App\Service\TaskServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route('category')]
class CategoryController extends AbstractController
{
    private CategoryServiceInterface $categoryService;

    private TaskServiceInterface $taskService;

    private NoteServiceInterface $noteService;

    private TranslatorInterface $translator;

    public function __construct(CategoryServiceInterface $categoryService, TaskServiceInterface $taskService, NoteServiceInterface $noteService, TranslatorInterface $translator)
    {
        $this->categoryService = $categoryService;
        $this->taskService = $taskService;
        $this->noteService = $noteService;
        $this->translator = $translator;
    }

    #[Route(
        '',
        name: 'category_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $page = $request->query->getAlnum('page', 1);
        $pagination = $this->categoryService->getPaginatedList($page);
        return $this->render(
            'category/index.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * View a category.
     *
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'category_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Request $request, Category $category): Response
    {
        $page = $request->query->getAlnum('page', 1);
        $categoryTasks = $this->taskService->getPaginatedListByCategory($page, $category);
        $categoryNotes = $this->noteService->getPaginatedListByCategory($page, $category);

        return $this->render(
            'category/show.html.twig', [
                'category' => $category,
                'tasks' => $categoryTasks,
                'notes' => $categoryNotes
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'category_create', methods: 'GET|POST' )]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['action' => $this->generateUrl('category_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/create.html.twig',  ['form' => $form->createView()]);
    }


    #[Route(
        '/{id}/edit',
        name: 'category_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'PUT']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash(
                'success',
                $this->translator->trans('category.edited_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', ['category' => $category, 'form' => $form->createView()]);
    }

    #[Route(
        '/{id}/delete',
        name: 'category_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'DELETE']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            FormType::class,
            $category,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->categoryService->delete($category))  {
                $this->addFlash(
                    'success',
                    $this->translator->trans('category.deleted_successfully')
                );
            }
            else {
                $this->addFlash(
                    'warning',
                    $this->translator->trans('category.cant_be_deleted')
                );
            }

            return $this->redirect('category_index');
        }

        return $this->render('category/delete.html.twig', ['category' => $category, 'form' => $form->createView()]);
    }
}