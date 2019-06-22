<?php

namespace App\Controller;

use App\Service\MaterialService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiMaterialController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiMaterialController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var MaterialService */
    private $materialService;

    /**
     * ApiMaterialController constructor.
     * @param SerializerInterface $serializer
     * @param MaterialService $materialService
     */
    public function __construct(SerializerInterface $serializer, MaterialService $materialService)
    {
        $this->serializer = $serializer;
        $this->materialService = $materialService;
    }

    /**
     * @Rest\Post("/api/material/create", name="createMaterial")
     * @param Request $request
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function createAction(Request $request): JsonResponse
    {
        $name = $request->request->get('name');
        $isActive = $request->request->get('isActive');
        $serialNum = $request->request->get('serialNum');
        $materialEntity = $this->materialService->createMaterial($message,$isActive,$serialNum);
        $data = $this->serializer->serialize($materialEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Get("/api/materials", name="getAllMaterials")
     * @return JsonResponse
     */
    public function getAllActions(): JsonResponse
    {
        $materialEntities = $this->materialService->getAll();
        $data = $this->serializer->serialize($materialEntities, 'json');

        return new JsonResponse($data, 200, [], true);
    }
}
