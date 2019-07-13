<?php

namespace App\Controller;

use App\Service\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ApiCategoryController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiCategoryController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var CategoryService */
    private $categoryService;

    /**
     * ApiCategoryController constructor.
     * @param SerializerInterface $serializer
     * @param CategoryService $categoryService
     */
    public function __construct(SerializerInterface $serializer, CategoryService $categoryService)
    {
        $this->serializer = $serializer;
        $this->categoryService = $categoryService;
    }

    /**
     * @Rest\Post("/api/category/create", name="createCategory")
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
        $type = $request->request->get('type');
        // $quantity = $request->request->get('quantity');
        $categoryEntity = $this->categoryService->createCategory($name,$type);
        $data = $this->serializer->serialize($categoryEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Post("/api/category/edit", name="editCategory")
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function editAction(CategoryRepository $categoryRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $type = $request->request->get('type');
        //var_dump($type['type']);
        $categoryEntity = $this->categoryService->editCategory($id,$type);
        $data = $this->serializer->serialize($categoryEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
    /**
     * @Rest\Post("/api/category/update", name="updateCategory")
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function updateAction(CategoryRepository $categoryRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $type = $request->request->get('type');
        // $quantity = $request->request->get('quantity');
        //var_dump($type['type']);
        $categoryEntity = $this->categoryService->updateCategory($id,$name,$type);
        $data = $this->serializer->serialize($categoryEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }
    /**
     * @Rest\Post("/api/category/delete", name="deleteCategory")
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(CategoryRepository $categoryRepository, Request $request): JsonResponse
    {
        
        $id = $request->request->get('id');
        // var_dump($id);
       $em = $this->getDoctrine()->getManager();
       $categorys = $categoryRepository->findById($id);
       foreach ($categorys as $category) {
            $this->writeLog("Suppression de la catégorie : <strong>".$category->getName()."</strong> # ".date('Y-m-d H:i:s'));
            $em->remove($category);
        }

       $em->flush();

       $data = $this->serializer->serialize($categorys, 'json');

        return new JsonResponse($data, 200, [], true);
    }  
    /**
     * @Rest\Get("/api/categorys", name="getAllCategorys")
     * @return JsonResponse
     */
    public function getAllActions(): JsonResponse
    {
        $categoryEntities = $this->categoryService->getAll();
        $data = $this->serializer->serialize($categoryEntities, 'json');
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
