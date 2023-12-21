<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private $userRepository;
    private $serializerInterface;
    private $validator;
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->serializerInterface = $serializerInterface;
        $this->validator = $validator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Create new data in User entity
     *
     * @param [type] $content
     * @return JsonResponse
     */
    public function add($content): JsonResponse
    {
        try {
            // converti le contenu de la requette en objet User
            $user = $this->serializerInterface->deserialize($content, User::class, 'json');

            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setAvatar($user->getAvatar() ?? 'avatar-null.png');
            $user->setBanner($user->getBanner() ?? 'banner-null.png');
            $user->setDescription($user->getDescription() ?? 'Je n\'ai pas de description');
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setRoles(['ROLE_USER']);
        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        // valide l'objet User (permet de vérifier les assert de l'entité)
        $errors = $this->validator->validate($user);
        // si il y a des erreurs, on les retourne
        if (count($errors) > 0) {
            $dataErrors = [];
            foreach ($errors as $error) {
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // si il n'y a pas d'erreur, on enregistre l'objet User en base de données
        $this->userRepository->add($user, true);

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "User created successfully"], Response::HTTP_CREATED);
    }

    /**
     * Edit data in User entity
     *
     * @param [type] $loggedUser
     * @param [type] $content
     * @return JsonResponse
     */
    public function edit($loggedUser, $content): JsonResponse
    {
        // Vérifier si l'utilisateur existe
        if (!$loggedUser) {
            return new JsonResponse(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }

        try {
            // converti le contenu de la requette en objet User
            $updatedUser = $this->serializerInterface->deserialize($content, User::class, 'json');
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return new JsonResponse(["message" => "JSON invalide"], Response::HTTP_BAD_REQUEST);
        }
        // valide l'objet User (permet de vérifier les assert de l'entité)
        $errors = $this->validator->validate($updatedUser);
        // si il y a des erreurs, on les retourne
        if (count($errors) > 0) {
            $dataErrors = [];
            foreach ($errors as $error) {
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Mettre à jour les propriétés de l'utilisateur existant avec les nouvelles données
        $loggedUser->setUsername($updatedUser->getUsername());
        $loggedUser->setFirstname($updatedUser->getFirstname());
        $loggedUser->setLastname($updatedUser->getLastname());
        $loggedUser->setEmail($updatedUser->getEmail());
        $loggedUser->setPassword($this->passwordHasher->hashPassword($updatedUser, $updatedUser->getPassword()));
        $loggedUser->setDescription($updatedUser->getDescription() === "" ? 'Je n\'ai pas de description' : $updatedUser->getDescription());
        $loggedUser->setAvatar($updatedUser->getAvatar() === "" ? 'http://placehold.it/300x300' : $updatedUser->getAvatar());
        $loggedUser->setBanner($updatedUser->getBanner() === "" ? 'http://placehold.it/500x500' : $updatedUser->getBanner());
        $loggedUser->setPhoneNumber($updatedUser->getPhoneNumber());

        // si il n'y a pas d'erreur, on enregistre l'objet User en base de données
        $this->userRepository->add($loggedUser, true);

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "User modified successfully"], Response::HTTP_OK);
    }
}
