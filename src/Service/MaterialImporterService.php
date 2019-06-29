<?php

namespace App\Service;

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

    /**
     * MaterialImporterService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
                $filename = $originalFileName . '-' . $partCount . '.' . $file->getClientOriginalExtension();
                $fullPath = $outputFolder . '/' . $filename;
                $output = fopen($fullPath, 'w');
                $files[] = $filename;
            }
            if ($line = fgetcsv($fh)) {
                fputcsv($output, $line);
            }

            $i++;
        }

        fclose($fh);
        fclose($output);

        return $files;
    }

}