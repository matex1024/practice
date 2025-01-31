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
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');
        $room = $request->query->get('room');


        if (!isset($dateFrom) && isset($dateTo) && isset($room)) {
            $reports = $this->getDummyData();
        } else {
            $reports = $reportRepository->findAll(
                ['date_from' => $dateFrom, 'date_to' => $dateTo, 'room' => $room], 
                ['room' => 'DESC', 'date_time' => 'DESC'],
            );
        }

        if (!$reports) {
            throw $this->createNotFoundException(
                'No reports found for given criteria'
            );
        }

        return $this->json($reports);
    }


    private function getDummyData(): array
    {
        return [
            [
                'id' => 1,
                'room' => '101',
                'date_time' => '2023-10-01 10:00:00+00',
                'name' => 'report 1',
                'user_name' => 'user 1',
            ],
            [
                'id' => 2,
                'room' => '102',
                'date_time' => '2023-10-02 11:00:00+00',
                'name' => 'report 2',
                'user_name' => 'user 2',
            ],
            [
                'id' => 3,
                'room' => '103',
                'date_time' => '2023-10-03 12:00:00+00',
                'name' => 'report 3',
                'user_name' => 'user 3',
            ],
        ];
    }
}
