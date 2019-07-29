<?php

namespace App\Controller;

use App\Service\DashboardService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\DashboardRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ApiDashboardController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiDashboardController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var DashboardService */
    private $dashboardService;

    /**
     * ApiDashboardController constructor.
     * @param SerializerInterface $serializer
     * @param DashboardService $dashboardService
     */
    public function __construct(SerializerInterface $serializer, DashboardService $dashboardService)
    {
        $this->serializer = $serializer;
        $this->dashboardService = $dashboardService;
    }

    /**
     * @Rest\Get("/api/dashboards", name="getAllDashboards")
     * @return JsonResponse
     */
    public function getAllActions(): JsonResponse
    {
        $data = $this->dashboardService->getAll();
        $data = $this->serializer->serialize($data, 'json');
        return new JsonResponse($data, 200, [], true);
    }
}
