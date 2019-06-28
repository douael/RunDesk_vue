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

    /**
     * ApiEmployeeController constructor.
     * @param SerializerInterface $serializer
     * @param EmployeeService $employeeService
     */
    public function __construct(SerializerInterface $serializer, EmployeeService $employeeService)
    {
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
        $user_id = $request->request->get('user_id');
        $employeeEntity = $this->employeeService->createEmployee($lastname, $firstname, $site, $user_id);
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
        $user_id = $request->request->get('user_id');

        $employeeEntity = $this->employeeService->updateEmployee($lastname, $firstname, $site, $user_id);
        $data = $this->serializer->serialize($employeeEntity, 'json');

        return new JsonResponse($data, 200, [], true);
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
        return new JsonResponse($data, 200, [], true);
    }
}
