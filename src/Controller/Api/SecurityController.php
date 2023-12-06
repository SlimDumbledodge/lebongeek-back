<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

class SecurityController extends AbstractController
{

    private $jwtManager;
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, JWTTokenManagerInterface $jwtManager)
    {
    $this->jwtManager = $jwtManager;
    $this->tokenStorage = $tokenStorage;
    }

    // /**
    //  * @Route("/api/login_check", name="login_check", methods={"GET"})
    //  *
    //  * @return JsonResponse
    //  */
    // public function login_check()
    // {
    //     $decodedJwtToken = $this->jwtManager->decode($this->tokenStorage->getToken());
    //     if ($decodedJwtToken instanceof TokenInterface) {

    //         $user = $decodedJwtToken->getUser();
    //         dd($user);
    //         return $user;

    //     } else {
    //         return null;
    //     }
    // }

}