<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskType;
use App\Service\TaskService;
use App\Service\TaskServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}