<?php

namespace App\Controller;

use App\Service\BorrowingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\BorrowingRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ApiBorrowingController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiBorrowingController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var BorrowingService */
    private $borrowingService;

    /**
     * ApiBorrowingController constructor.
     * @param SerializerInterface $serializer
     * @param BorrowingService $borrowingService
     */
    public function __construct(SerializerInterface $serializer, BorrowingService $borrowingService)
    {
        $this->serializer = $serializer;
        $this->borrowingService = $borrowingService;
    }

    /**
     * @Rest\Post("/api/borrowing/create", name="createBorrowing")
     * @param Request $request
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function createAction(Request $request): JsonResponse
    {   
        $employee = $request->request->get('employee');
        $material = $request->request->get('material');

        $date_start = new \DateTime($request->request->get('date_start'));
        $date_end = new \DateTime($request->request->get('date_end'));

        $borrowingEntity = $this->borrowingService->createBorrowing($employee,$material,$date_start,$date_end);
        $data = $this->serializer->serialize($borrowingEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Post("/api/borrowing/update", name="updateBorrowing")
     * @param Request $request
     * @param BorrowingRepository $borrowingRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function updateAction(BorrowingRepository $borrowingRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $user = $request->request->get('user');
        $employee = $request->request->get('employee');
        $materiel = $request->request->get('materiel');

        // $date_start = new \DateTime($request->request->get('date_start'));
        // $date_end = new \DateTime($request->request->get('date_end'));

        $date_start = NULL;
        $date_end = NULL;
        $borrowingEntity = $this->borrowingService->updateBorrowing($id,$user,$employee,$materiel,$date_start,$date_end);
        $data = $this->serializer->serialize($borrowingEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
        /**
     * @Rest\Post("/api/borrowing/restitute", name="restituteMaterial")
     * @param Request $request
     * @param BorrowingRepository $borrowingRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function restituteAction(BorrowingRepository $borrowingRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('borrowingId');
        $date_restitution = new \DateTime('now');;
        $borrowingEntity = $this->borrowingService->resituteMaterial($id,$date_restitution);
        $data = $this->serializer->serialize($borrowingEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
    /**
     * @Rest\Post("/api/borrowing/delete", name="deleteBorrowing")
     * @param BorrowingRepository $borrowingRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(BorrowingRepository $borrowingRepository, Request $request): JsonResponse
    {
        
        $id = $request->request->get('id');
       $em = $this->getDoctrine()->getManager();
       $borrowings = $borrowingRepository->findById($id);
       foreach ($borrowings as $borrowing) {
        $this->writeLog("Suppression de l'emprunt de material : <strong>".$borrowing->getMaterial()->getName()."</strong> pour l'employee : <strong>".$borrowing->getEmployee()->getFirstName()." ".$borrowing->getEmployee()->getFirstName()."</strong> # ".date('Y-m-d H:i:s'));
        $em->remove($borrowing);
        }

       $em->flush();

       $data = $this->serializer->serialize($borrowings, 'json');

        return new JsonResponse($data, 200, [], true);
    }  
    /**
     * @Rest\Get("/api/borrowings", name="getAllBorrowings")
     * @return JsonResponse
     */
    public function getAllActions(): JsonResponse
    {
        $borrowingEntities = $this->borrowingService->getAll();
        $data = $this->serializer->serialize($borrowingEntities, 'json');
        
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
