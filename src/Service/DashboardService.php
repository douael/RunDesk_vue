<?php

namespace App\Service;

use App\Entity\Dashboard;
use App\Repository\DashboardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class DashboardService extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var CategoryRepository */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->readLog();
    }

    public function readLog() {
        $chemin = $this->getParameter('ourlogs_directory');
        $chemin_url = $chemin . "/event-log.txt";
        $myfile = fopen($chemin_url, "r") or die("Unable to open file!");
        $text = fread($myfile,filesize($chemin_url));
        fclose($myfile);
        $text = array_reverse($text);
        $text = explode("\n", $text);
        $i = 0;
        $len = count($text);
        foreach ($text as &$line) {
            
            if( next( $text )){
                $line = explode("#", $line);
            //var_dump($line);
                $line = array(
                    'Actions'=>$line[0],
                    'Date'=>$line[1]
                );
            }
        }
        return $text;
    }



}
