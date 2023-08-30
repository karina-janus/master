<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskType;
use App\Service\TaskService;
use App\Service\TaskServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route('task')]
class TaskController extends AbstractController
{
    private TaskServiceInterface $taskService;

    private TranslatorInterface $translator;

    public function __construct(TaskServiceInterface $taskService, TranslatorInterface $translator)
    {
        $this->taskService = $taskService;
        $this->translator = $translator;
    }

    #[Route(
        '',
        name: 'task_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $page = $request->query->getAlnum('page', 1);
        $pagination = $this->taskService->getPaginatedList($page);

        return $this->render(
            'task/index.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * View a task.
     *
     * @param Task $task Task entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'task_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Task $task): Response
    {
        return $this->render(
            'task/show.html.twig',
            ['task' => $task]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'task_create', methods: 'GET|POST', )]
    public function create(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(
            TaskType::class,
            $task,
            ['action' => $this->generateUrl('task_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->save($task);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/create.html.twig',  ['form' => $form->createView()]);
    }


    #[Route(
        '/{id}/edit',
        name: 'task_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'PUT']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(
            TaskType::class,
            $task,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('task_edit', ['id' => $task->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->save($task);
            $this->addFlash(
                'success',
                $this->translator->trans('task.edited_successfully')
            );

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/edit.html.twig', ['task' => $task, 'form' => $form->createView()]);
    }

    #[Route(
        '/{id}/delete',
        name: 'task_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'DELETE']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Task $task): Response
    {
        $form = $this->createForm(
            FormType::class,
            $task,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('task_delete', ['id' => $task->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->delete($task);
            $this->addFlash(
                'success',
                $this->translator->trans('task.deleted_successfully')
            );

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/delete.html.twig', ['task' => $task, 'form' => $form->createView()]);
    }
}