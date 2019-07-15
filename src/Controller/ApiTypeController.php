<?php

namespace App\Controller;

use App\Service\TypeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\TypeRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ApiTypeController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiTypeController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var TypeService */
    private $typeService;

    /**
     * ApiTypeController constructor.
     * @param SerializerInterface $serializer
     * @param TypeService $typeService
     */
    public function __construct(SerializerInterface $serializer, TypeService $typeService)
    {
        $this->serializer = $serializer;
        $this->typeService = $typeService;
    }

    /**
     * @Rest\Post("/api/type/create", name="createType")
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
        $typeEntity = $this->typeService->createType($name);
        $data = $this->serializer->serialize($typeEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Post("/api/type/edit", name="editType")
     * @param Request $request
     * @param TypeRepository $typeRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function editAction(TypeRepository $typeRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $typeEntity = $this->typeService->editType($id);
        $data = $this->serializer->serialize($typeEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
    /**
     * @Rest\Post("/api/type/update", name="updateType")
     * @param Request $request
     * @param TypeRepository $typeRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function updateAction(TypeRepository $typeRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $typeEntity = $this->typeService->updateType($id,$name);
        $data = $this->serializer->serialize($typeEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
    /**
     * @Rest\Post("/api/type/delete", name="deleteType")
     * @param TypeRepository $typeRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(TypeRepository $typeRepository, Request $request): JsonResponse
    {
        
        $id = $request->request->get('id');
        // var_dump($id);
       $em = $this->getDoctrine()->getManager();
       $types = $typeRepository->findById($id);
       foreach ($types as $type) {
            $this->writeLog("Suppression du type : <strong>".$type->getName()."</strong> # ".date('Y-m-d H:i:s'));
            $em->remove($type);
        }

       $em->flush();

       $data = $this->serializer->serialize($types, 'json');

        return new JsonResponse($data, 200, [], true);
    }  
    /**
     * @Rest\Get("/api/types", name="getAllTypes")
     * @return JsonResponse
     */
    public function getAllActions(): JsonResponse
    {
        $typeEntities = $this->typeService->getAll();
        $data = $this->serializer->serialize($typeEntities, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    public function writeLog($phrase) {
        $chemin = $this->getParameter('logs_directory');
        if (!is_dir($chemin)) {
            mkdir($chemin, 0775, true);
        }
        $chemin_url = $chemin . "/event-log.txt";
        $handle = fopen($chemin_url, "a+");
        fputs($handle, $phrase."\n");
    }
}
