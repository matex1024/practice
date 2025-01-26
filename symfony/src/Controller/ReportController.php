<?php

namespace App\Controller;

use App\Entity\Report;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class ReportController extends AbstractController
{
    #[Route('/report', name: 'report')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }

    #[Route('/api/v1/reports', name: 'reports_show_all', format: 'json', methods: ['GET'])]
    public function showAll(
        Request $request, 
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $reportRepository = $entityManager->getRepository(Report::class);
        // $page = $request->query->getInt('page', 1);
        // $limit = $request->query->getInt('limit', 10);
        // $offset = ($page - 1) * $limit;

        $parameters = json_decode($request->getContent(), true);
        $filters = [
            'date_from' => $parameters['date_from']??null,
            'date_to' => $parameters['date_to']??null,
            'room' => $parameters['room']??null,
        ];

        $reports = $reportRepository->findAll(
            array_filter($filters), 
            ['room' => 'DESC', 'date_time' => 'DESC'],
        );

        if (!$reports) {
            throw $this->createNotFoundException(
                'No reports found for given criteria'
            );
        }

        return $this->json($reports);
    }
}
