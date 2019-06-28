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
     * @Route("/api/user/create", name="register")
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
}
