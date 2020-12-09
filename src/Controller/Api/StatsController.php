<?php

namespace App\Controller\Api;

use App\Repository\AdherentRepository;
use App\Repository\CommitteeRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends Controller
{
    /**
     * @Route("/stats", name="api_stats", methods={"GET"})
     */
    public function indexAction(
        AdherentRepository $adherentRepository,
        EventRepository $eventRepository,
        CommitteeRepository $committeeRepository
    ): Response {
        return new JsonResponse([
            'userCount' => $adherentRepository->countAdherents(),
            'eventCount' => $eventRepository->countElements(),
            'committeeCount' => $committeeRepository->countElements(),
        ]);
    }
}
