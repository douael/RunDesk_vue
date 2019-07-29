<?php

namespace App\Service;
use App\Service\EmployeeService;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class EmployeeImporterService
 * @package App\Service
 */
class EmployeeImporterService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var EmployeeService */
    private $employeeService;

    /**
     * EmployeeImporterService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, EmployeeService $employeeService)
    {
        $this->entityManager = $entityManager;
        $this->employeeService = $employeeService;

    }

    /**
     * @param UploadedFile $file
     * @param string $outputFolder
     * @param int $batchSize
     * @return array
     */
    public function splitFile(UploadedFile $file, string $outputFolder, int $batchSize = 10000): array
    {
        if (!is_dir($outputFolder)) {
            mkdir($outputFolder, 0775, true);
        }

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $fh = fopen($file->getRealPath(), 'rb');
        $files = [];
        $output = null;
        $i = 0;
        $partCount = 0;

        while (!feof($fh)) {
            if (($i % $batchSize) === 0) {
                if ($i > 0 && $output !== null) {
                    fclose($output);
                }

                $partCount++;
                $filename = $originalFileName.'-'.uniqid('', true). '.' . $file->getClientOriginalExtension();
                $fullPath = $outputFolder . '/' . $filename;
                $output = fopen($fullPath, 'w');
                $files[] = $filename;
            }
            if ($line = fgetcsv($fh)) {
                fputcsv($output, $line);
                $lastname = $line[0];
                $firstname = $line[1];
                $site = $line[2];

                $employeeEntity = $this->employeeService->createEmployee($lastname,$firstname,$site);
  
            }

            $i++;
        }

        fclose($fh);
        fclose($output);

        return $files;
    }

}