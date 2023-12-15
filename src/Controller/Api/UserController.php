<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Get all data from User entity
     * 
     * @Route("/api/users", name="app_api_users", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        return $this->json($users, Response::HTTP_OK, [], ["groups" => "users"]);
    }

    /**
     * Get data from User entity
     * 
     * @Route("/api/{id}/users", name="app_api_users_show", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        if (!$user) {
            return $this->json([
                "error" => "User not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK, [], ["groups" => "users"]);
    }

    /**
     * Create new data in User entity
     * 
     * @Route("/api/users", name="app_api_users_new", methods={"POST"})
     *
     * @param Request $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function create(Request $request, UserService $userService): JsonResponse
    {
        //recupere le contenu de la requette (json) que j'envoi au UserServce pour la partie logique
        return $userService->add($request->getContent());
    }

    /**
     * Edit data in User entity
     * 
     * @Security("is_granted('ROLE_USER') and user === loggedUser")
     * @Route("/api/{id}/users", name="app_api_users_update", methods={"PUT"})
     *
     * @param Request $request
     * @param User $loggedUser
     * @param UserService $userService
     * @return JsonResponse
     */
    public function update(Request $request, User $loggedUser, UserService $userService): JsonResponse
    {
        // j'utilise la method edit de mon UserService pour lui fournir le contenu reçu par la requête et le user à modifier
        return $userService->edit($loggedUser, $request->getContent());
    }

    /**
     * Delete data from User entity
     *
     * @Security("is_granted('ROLE_USER') and user === loggedUser")
     * @Route("/api/{id}/users", name="app_api_users_delete", methods={"DELETE"})
     * @param UserRepository $userRepository
     * @param User $loggedUser
     * @return JsonResponse
     */
    public function delete(UserRepository $userRepository, User $loggedUser): JsonResponse
    {
        // si l'utilisateur n'existe pas, on retourne une reponse 404
        if (!$loggedUser) {
            return $this->json(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }

        $userRepository->remove($loggedUser, true);
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "User deleted successfully"], Response::HTTP_OK);
    }

    /**
     * Get the logged user
     * 
     * @IsGranted("ROLE_USER")
     * @Route("/api/get_user", name="app_api_current_user", methods={"GET"})
     * @return void
     */
    public function getCurrentUser()
    {
        $currentUser = $this->getUser();

        return $this->json($currentUser, Response::HTTP_OK, [], ["groups" => "users"]);
    }
}
