<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login_check", name="login_check", methods={"GET","POST"})
     *
     * @return JsonResponse
     */
    public function login_check(Request $request)
    {
        $tokenExtractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $token = $tokenExtractor->extract($request);

        if ($token) {
        // Validez le token
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
        $userToken = $jwtManager->decode($token);

        // Récupérez les données de l'utilisateur à partir du token
        $userData = $userToken->getUser();
            dd($userData);
        // Votre logique métier avec les données de l'utilisateur
        // ...
        }}
}