<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskService;
use App\Service\TaskServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('task')]
class TaskController extends AbstractController
{
    private TaskServiceInterface $taskService;
    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
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

    #[Route(
        '/{id}',
        name: 'task_show',
        methods: 'GET',
        requirements: ['id' => '[1-9]\d*']
    )]
    public function show(Task $task): Response
    {
        return $this->render(
            'task/show.html.twig',
            ['task' => $task]
        );
    }
}