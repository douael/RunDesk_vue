<?php

namespace App\Service;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UserService extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var UserRepository */
    private $userRepository;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $login
     * @param string $password
     * @param string $role
     * @return User
     */
    public function createUser(string $login, string $password, array $role): User
    {
        $userEntity = new User();
        $userEntity->setLastname($login);
        $userEntity->setFirstname($password);
        $userEntity->setSite($role);
        $this->em->persist($userEntity);
        $this->em->flush();
        return $userEntity;
    }
    
    /**
     * @param integer $id
     * @param string $login
     * @param string $password
     * @param string $role
     * @return User
     */
    public function updateUser(int $id,string $login,string $password, string $role): User
    {
        
        $user = $this->em->getRepository(User::class)->find($id);

        $user->setLastname($login);
        $user->setFirstname($password);
        $user->setSite($role);
        $this->em->flush();

        return $user;
    }
    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(User::class)->findBy([], ['id' => 'DESC']);
    }
}
