<?php

namespace App\Service;
use App\Service\MaterialService;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Entity\Material;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MaterialImporterService
 * @package App\Service
 */
class MaterialImporterService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var MaterialService */
    private $materialService;

    /** @var CategoryRepository */
    private $cat;
    /**
     * MaterialImporterService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, MaterialService $materialService, CategoryRepository $cat)
    {
        $this->entityManager = $entityManager;
        $this->materialService = $materialService;
        $this->cat = $cat;

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
                
                $name = $line[0];
                $serialNumber = $line[1];
                $category['id'] = 3;

                $materialEntity = $this->materialService->createMaterial($name,1,$serialNumber, $category);
  
            }

            $i++;
        }

        fclose($fh);
        fclose($output);

        return $files;
    }

}