<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;


final class ApiSecurityController extends AbstractController
{
    /**
     * @Route("/api/security/login", name="login")
     * @return JsonResponse
     */
    public function loginAction(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->writeLog("Connexion de l'utilisateur : ".$user->getLogin()." - ".date('Y-m-d H:i:s'));
        $response = new JsonResponse($user->getRoles());
        return $response;
    }



    /**
     * @Route("/api/security/logout", name="logout")
     * @return void
     * @throws \RuntimeException
     */
    public function logoutAction(): void
    {
        throw new \RuntimeException('This should not be reached!');
    }

    /**
     * @Route("/api/register/create", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $login = $request->request->get('login');
        if (empty($name)) {
            throw new BadRequestHttpException('login cannot be empty');
        }
        $login = $request->request->get('login');
        $password = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()); // $request->request->get('plainPassword')->;
        $role = ['ROLE_ADMIN'];
        $userEntity = $this->userService->createUser($login,$password,$role);
        $data = $this->serializer->serialize($userEntity, 'json');

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
