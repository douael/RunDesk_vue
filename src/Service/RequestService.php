<?php

namespace App\Service;

use App\Entity\request;
use Doctrine\ORM\EntityManagerInterface;

final class RequestService
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * requestService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     * @return Request
     */
    public function createrequest(string $title,text $description,boolean $status,datetime $created_at,datetime $updated_at): Request
    {

        $requestEntity = new Request();
        $requestEntity->setTitle($title);
        $requestEntity->setDescription($description);
        $requestEntity->setStatus($status);
        $requestEntity->setCreatedAt($created_at);
        $requestEntity->setUpdatedAt($updated_at);

        $this->em->persist($requestEntity);
        $this->em->flush();

        return $requestEntity;
    }

    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Request::class)->findBy([], ['id' => 'DESC']);
    }
}
