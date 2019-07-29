<?php

namespace App\Controller;

use App\Service\MaterialService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\MaterialRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\MaterialImporterService;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        if (empty($name)) {
            throw new BadRequestHttpException('name cannot be empty');
        }
        $isActive = $request->request->get('isActive');
        $category = $request->request->get('category');
        
        $category =explode(" - ",$category);
        $serialNumber = $request->request->get('serialNumber');
        $materialEntity = $this->materialService->createMaterial($name,$isActive,$serialNumber, $category);
        $data = $this->serializer->serialize($materialEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Post("/api/material/import", name="importMaterial")
     * @param Request $request
     * @param MaterialImporterService $service
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function importAction(Request $request, MaterialImporterService $service ): JsonResponse
    {

        $file = $request->files->get('file');
        if (!$file instanceof UploadedFile) {
            return new JsonResponse('Invalid File', 422);
        }

        $outputFolder = $this->getParameter('materials_directory');
        $files = $service->splitFile($file, $outputFolder);

        var_dump($file);die;

        unlink($file->getRealPath());
        return new JsonResponse([]);

    }

    /**
     * @Rest\Post("/api/material/edit", name="editMaterial")
     * @param Request $request
     * @param MaterialRepository $materialRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function editAction(MaterialRepository $materialRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $isActive = $request->request->get('isActive');
        //var_dump($isActive['isActive']);
        $materialEntity = $this->materialService->editMaterial($id,$isActive);
        $data = $this->serializer->serialize($materialEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Post("/api/material/available", name="availableMaterial")
     * @param Request $request
     * @param MaterialRepository $materialRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function availableAction(MaterialRepository $materialRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $available = $request->request->get('available');
        //var_dump($isActive['isActive']);
        $materialEntity = $this->materialService->availableMaterial($id,$available);
        $data = $this->serializer->serialize($materialEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
    /**
     * @Rest\Post("/api/material/update", name="updateMaterial")
     * @param Request $request
     * @param MaterialRepository $materialRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function updateAction(MaterialRepository $materialRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $isActive = $request->request->get('isActive');
        $serialNumber = $request->request->get('serialNumber');
        $category = $request->request->get('category');
        //var_dump($isActive['isActive']);
        $materialEntity = $this->materialService->updateMaterial($id,$name,$isActive,$serialNumber, $category);
        $data = $this->serializer->serialize($materialEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
    /**
     * @Rest\Post("/api/material/delete", name="deleteMaterial")
     * @param MaterialRepository $materialRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(MaterialRepository $materialRepository, Request $request): JsonResponse
    {
        
        $id = $request->request->get('id');
        // var_dump($id);
       $em = $this->getDoctrine()->getManager();
       $materials = $materialRepository->findById($id);
       foreach ($materials as $material) {
            $this->writeLog("Suppression du matériel : ".$material->getName()." # ".date('Y-m-d H:i:s'));
            $em->remove($material);
        }

        $em->flush();

        $data = $this->serializer->serialize($materials, 'json');

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

    public function writeLog($phrase) {
        $chemin = $this->getParameter('ourlogs_directory');
        if (!is_dir($chemin)) {
            mkdir($chemin, 0775, true);
        }
        $chemin_url = $chemin . "/event-log.txt";
        $handle = fopen($chemin_url, "a+");
        fputs($handle, $phrase."\n");
    }
}
