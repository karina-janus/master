<?php

namespace App\Controller;

use App\Repository\RecordRepository;
use App\Repository\RecordRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/record')]
class RecordController extends AbstractController
{
    #[Route(
        '',
        name: 'record_index',
        methods: 'GET'
    )]
    public function index(RecordRepositoryInterface $recordRepository): Response
    {
        $records = $recordRepository->findAll();

        return $this->render(
            'record/index.html.twig',
            ['records' => $records]
        );
    }

    #[Route(
        '/{id}',
        name: 'record_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function view(RecordRepositoryInterface $recordRepository, int $id): Response
    {
        $record = $recordRepository->findOneById($id);

        return $this->render(
            'record/view.html.twig',
            ['record' => $record]
        );
    }
}