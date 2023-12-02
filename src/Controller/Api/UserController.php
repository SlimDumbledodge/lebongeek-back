<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="app_api_users", methods={"GET"})
     */
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        
        return $this->json($users, Response::HTTP_OK, [], ["groups" => "users"]);
    }



    /**
     * @param UserRepository $userRepository
     * @param integer $id
     * @return JsonResponse
     * @Route("/api/{id}/users", name="app_api_users_show", methods={"GET"})
     */
    public function show(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json([
                "error" => "User not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK, [], ["groups" => "users"]);
    }



    /**
     * @return JsonResponse
     * @Route("/api/users", name="app_api_users_new", methods={"POST"})
     */
    public function create(Request $request,UserRepository $userRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator): JsonResponse
    {
        //recupere le contenu de la requette (json)
        $content = $request->getContent();

        try {
            // converti le contenu de la requette en objet User
            $user = $serializerInterface->deserialize($content, User::class, 'json');
        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return $this->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
            // valide l'objet User (permet de vérifier les assert de l'entité)
            $errors = $validator->validate($user);
            // si il y a des erreurs, on les retourne
            if (count($errors) > 0) {
                $dataErrors = [];
            foreach($errors as $error){
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
                return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            /* $user->setPassword($this->passwordHasher->hashPassword($user, 'password')); */
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setRoles(['ROLE_USER']);
            // si il n'y a pas d'erreur, on enregistre l'objet User en base de données
            $userRepository->add($user,true);
            
        
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "User created successfully"], Response::HTTP_CREATED);
    }



    /**
     * @return JsonResponse
     * @Route("/api/{id}/users", name="app_api_users_update", methods={"PUT"})
     */
    public function update(Request $request, UserRepository $userRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator, int $id): JsonResponse
    {

        // Récupérer l'utilisateur existant par son ID
        $existingUser = $userRepository->find($id);
            
        // Vérifier si l'utilisateur existe
        if (!$existingUser) {
            return $this->json(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }

        //recupere le contenu de la requette (json)
        $content = $request->getContent();

        try {
            // converti le contenu de la requette en objet User
            $updatedUser = $serializerInterface->deserialize($content, User::class, 'json');
        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return $this->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
            // valide l'objet User (permet de vérifier les assert de l'entité)
            $errors = $validator->validate($updatedUser);
            // si il y a des erreurs, on les retourne
            if (count($errors) > 0) {
                $dataErrors = [];
            foreach($errors as $error){
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
                return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }  

            // Mettre à jour les propriétés de l'utilisateur existant avec les nouvelles données
            $existingUser->setUsername($updatedUser->getUsername());
            $existingUser->setFirstname($updatedUser->getFirstname());
            $existingUser->setAvatar($updatedUser->getAvatar() ?? 'http://placehold.it/300x300');
            $existingUser->setPhoneNumber($updatedUser->getPhoneNumber());
            $existingUser->setDescription($updatedUser->getDescription() ?? 'Je n\'ai pas de description');

            // si il n'y a pas d'erreur, on enregistre l'objet User en base de données
            $userRepository->add($existingUser,true);
            
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "User modified successfully"], Response::HTTP_OK);
    }



    /**
     * @return JsonResponse
     * @Route("/api/{id}/users", name="app_api_users_delete", methods={"DELETE"})
     */
    public function delete(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);
        // si l'utilisateur n'existe pas, on retourne une reponse 404
        if (!$user) {
            return $this->json(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }

        $userRepository->remove($user, true);
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "User deleted successfully"], Response::HTTP_OK);
    }



}
