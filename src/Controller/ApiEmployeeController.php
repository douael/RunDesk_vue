<?php

namespace App\Controller;

use App\Service\EmployeeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EmployeeRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\EmployeeImporterService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Borrowing;
/**
 * Class ApiEmployeeController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiEmployeeController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var EmployeeService */
    private $employeeService;

    /** @var EntityManagerInterface */
    private $em;
    /**
     * ApiEmployeeController constructor.
     * @param SerializerInterface $serializer
     * @param EmployeeService $employeeService
     */
    public function __construct(SerializerInterface $serializer, EmployeeService $employeeService, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->employeeService = $employeeService;
    }

    /**
     * @Rest\Post("/api/employee/create", name="createEmployee")
     * @param Request $request
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function createAction(Request $request): JsonResponse
    {
        $lastname = $request->request->get('lastname');
        $firstname = $request->request->get('firstname');
        $site = $request->request->get('site');
        // $user_id = $request->request->get('user_id');
        $employeeEntity = $this->employeeService->createEmployee($lastname, $firstname, $site);
        $data = $this->serializer->serialize($employeeEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    
    /**
     * @Rest\Post("/api/employee/update", name="updateEmployee")
     * @param Request $request
     * @param EmployeeRepository $employeeRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function updateAction(EmployeeRepository $employeeRepository, Request $request): JsonResponse
    {
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $lastname = $request->request->get('lastname');
        $firstname = $request->request->get('firstname');
        $site = $request->request->get('site');
        // $user_id = $request->request->get('user_id');

        $employeeEntity = $this->employeeService->updateEmployee($id,$firstname, $lastname, $site);
        $data = $this->serializer->serialize($employeeEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Post("/api/employee/import", name="importEmployee")
     * @param Request $request
     * @param EmployeeImporterService $service
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function importAction(Request $request, EmployeeImporterService $service ): JsonResponse
    {

        $file = $request->files->get('file');
        if (!$file instanceof UploadedFile) {
            return new JsonResponse('Invalid File', 422);
        }

        $outputFolder = $this->getParameter('employees_directory');
        $files = $service->splitFile($file, $outputFolder);

        var_dump($file);die;

        unlink($file->getRealPath());
        return new JsonResponse([]);

    }

    /**
     * @Rest\Post("/api/employee/delete", name="deleteEmployee")
     * @param EmployeeRepository $employeeRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(EmployeeRepository $employeeRepository, Request $request): JsonResponse
    {
        
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $employees = $employeeRepository->findById($id);
        foreach ($employees as $employee) {
            $this->writeLog("Suppression de l'employe : <strong>".$employee->getFirstname()." ".$employee->getLastname()."</strong> # ".date('Y-m-d H:i:s'));
            $em->remove($employee);
        }

       $em->flush();

       $data = $this->serializer->serialize($employees, 'json');

        return new JsonResponse($data, 200, [], true);
    }  
    /**
     * @Rest\Get("/api/employees", name="getAllEmployees")
     * @return JsonResponse
     */
    public function getAllActions(): JsonResponse
    {
        $employeeEntities = $this->employeeService->getAll();
        $data = $this->serializer->serialize($employeeEntities, 'json');
        $emps = json_decode($data,true);

        foreach($emps as &$emp){
            $borrowings = $this->em->getRepository(Borrowing::class)->findByEmployee($emp['id']);
            $emp['count']=count($borrowings);
       
        }
        $data = $this->serializer->serialize($emps, 'json');
        return new JsonResponse($data, 200, [], true);

        $data = $this->serializer->serialize($employeeEntities, 'json');
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
