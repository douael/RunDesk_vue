<?php

namespace App\Controller;

use App\Service\BorrowingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\BorrowingRepository;
use App\Repository\EmployeeRepository;
use App\Repository\MaterialRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Dompdf\Dompdf;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Borrowing;
use Dompdf\Options;
/**
 * Class ApiBorrowingController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiBorrowingController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var EntityManagerInterface */
    private $em;
    
    /** @var BorrowingService */
    private $borrowingService;

    /**
     * ApiBorrowingController constructor.
     * @param SerializerInterface $serializer
     * @param BorrowingService $borrowingService
     * @param EntityManagerInterface $em
     */
    public function __construct(SerializerInterface $serializer, BorrowingService $borrowingService,EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $material =explode(" - ",$material);
        $employee =explode(" - ",$employee);

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
     * @param EmployeeRepository $employeeRepository
     * @param MaterialRepository $materialRepository
     * @return JsonResponse
     * @IsGranted("ROLE_FOO")
     */
    public function restituteAction(BorrowingRepository $borrowingRepository,EmployeeRepository $employeeRepository,MaterialRepository $materialRepository, Request $request): JsonResponse
    {
        //var_dump($request->request);
        $id = $request->request->get('borrowingId');
        $date_restitution = new \DateTime('now');;
        $borrowingEntity = $this->borrowingService->resituteMaterial($id,$date_restitution);
        $data = $this->serializer->serialize($borrowingEntity, 'json');
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $id = $request->request->get('borrowingId');
        $borrowing = $this->em->getRepository(Borrowing::class)->find($id);
        $employeeF =$borrowing->getEmployee()->getFirstname();
        $employeeL =$borrowing->getEmployee()->getLastname();
        $material = $borrowing->getMaterial()->getName();
        $serialNumber = $borrowing->getMaterial()->getSerialNumber();
        $category = $borrowing->getMaterial()->getCategory();
        $categoryN = $category->getName();
        $type = $category->getType()->getName();
        $employeeS = $borrowing->getEmployee()->getSite();
        $date_start = $borrowing->getDateStart();
        $date_end = $borrowing->getDateEnd();
        $date_restitution = $borrowing->getDateRestitution();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('default/mypdf.html.twig', [
            'borrowing' => $borrowing,
            'employeeL' => $employeeL,
            'employeeF' => $employeeF,
            'employeeS' => $employeeS,
            'material' => $material,
            'serialNumber' => $serialNumber,
            'categoryN' => $categoryN,
            'type' => $type,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'date_restitution' => $date_restitution,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('pdf_directory');
            if (!is_dir($publicDirectory)) {
            mkdir($publicDirectory, 0775, true);
            }
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/borrowing'.$id.'.pdf';
        
        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);
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
        $this->writeLog("Suppression de l'emprunt de material : ".$borrowing->getMaterial()->getName()." pour l'employee : ".$borrowing->getEmployee()->getFirstName()." ".$borrowing->getEmployee()->getFirstName()." # ".date('Y-m-d H:i:s'));
        $em->remove($borrowing);
        }

       $em->flush();

       $data = $this->serializer->serialize($borrowings, 'json');

        return new JsonResponse($data, 200, [], true);
    }  
      /**
     * @Rest\Post("/api/borrowing/uploadHistory", name="uploadHistory")
     * @param BorrowingRepository $borrowingRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadHistory(BorrowingRepository $borrowingRepository, Request $request): JsonResponse
    {
        $date = $request->request->get('date');
        
       $borrowings = $borrowingRepository->findByDate($date);

       $chemin = $this->getParameter('history_directory');
       if (!is_dir($chemin)) {
           mkdir($chemin, 0775, true);
       }
       foreach($borrowings as $key => $value){
           
        $borrowing = $this->em->getRepository(Borrowing::class)->find($borrowings[$key]['id']);
           $borrowings[$key]['employee_id'] =  $borrowing->getEmployee()->getFirstname().' '.$borrowing->getEmployee()->getLastname();
           $borrowings[$key]['material_id'] =  $borrowing->getMaterial()->getName();
           $borrowings[$key]['serialNumber'] = $borrowing->getMaterial()->getSerialNumber();
         } 
    //    var_dump($borrowings);
       $filename = "history".uniqid();
    $chemin_url = $chemin .'/'. $filename .".csv";
     $firstline = array('id','Date de debut','Date de fin','Date de restitution','Nom de l\'employee','Nom du materiel','Numero de serie');
    $fp = fopen($chemin_url, 'w');
    fputcsv($fp,$firstline);
     foreach ($borrowings as $fields) {
         fputcsv($fp, $fields);
     }
     
     fclose($fp);
        
          $data = $this->serializer->serialize($filename, 'json');

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
        $chemin = $this->getParameter('ourlogs_directory');
        if (!is_dir($chemin)) {
            mkdir($chemin, 0775, true);
        }
        $chemin_url = $chemin . "/event-log.txt";
        $handle = fopen($chemin_url, "r+");
        fputs($handle, $phrase."\n");
    }

}
