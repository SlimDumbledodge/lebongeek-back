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

    const REGEX = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\d\s])\S{8,}$/";

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
            // si le nom d'utilisateur est vide, on renvoi une erreur
            if ($user->getUsername() === "") {
                return new JsonResponse("Veuillez renseigner un nom d'utilisateur", Response::HTTP_BAD_REQUEST);
            }
            // si l'email est vide, on renvoi une erreur
            if ($user->getEmail() === "") {
                return new JsonResponse("Veuillez renseigner un email", Response::HTTP_BAD_REQUEST);
            }
            // si le numéro de téléphone est vide, on renvoi une erreur
            if ($user->getPhoneNumber() === "") {
                return new JsonResponse("Veuillez renseigner un numéro de téléphone", Response::HTTP_BAD_REQUEST);
            }
            // si le password est vide, on renvoi une erreur
            if ($user->getPassword() === "") {
                return new JsonResponse("Veuillez renseigner un mot de passe", Response::HTTP_BAD_REQUEST);
            }
            // si le mot de passe fait moins de 8 caractères, ne contient pas au moins une majuscule, une minuscule, un chiffre et un caractère spécial, on renvoi une erreur
            if (!preg_match(self::REGEX, $user->getPassword())) {
                return new JsonResponse("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial", Response::HTTP_BAD_REQUEST);
            }
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setAvatar($user->getAvatar() ?? 'avatar-null.jpg');
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
            // si le nom d'utilisateur est vide, on ne le modifie pas
            if ($updatedUser->getUsername() !== "") {
                $loggedUser->setUsername($updatedUser->getUsername());
            }
            // si l'email est vide, on ne le modifie pas
            if ($updatedUser->getEmail() !== "") {
                $loggedUser->setEmail($updatedUser->getEmail());
            }
            // si le numéro de téléphone est vide, on ne le modifie pas
            if ($updatedUser->getPhoneNumber() !== "") {
                $loggedUser->setPhoneNumber($updatedUser->getPhoneNumber());
            }
            // si le password est vide, on ne le modifie pas
            if ($updatedUser->getPassword() !== "") {
                // si le mot de passe fait moins de 8 caractères, ne contient pas au moins une majuscule, une minuscule, un chiffre et un caractère spécial, on renvoi une erreur
                if (!preg_match(self::REGEX, $updatedUser->getPassword())) {
                    return new JsonResponse("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial", Response::HTTP_BAD_REQUEST);
                }
                $loggedUser->setPassword($this->passwordHasher->hashPassword($updatedUser, $updatedUser->getPassword()));
            }

            $loggedUser->setFirstname($updatedUser->getFirstname());
            $loggedUser->setLastname($updatedUser->getLastname());
            $loggedUser->setDescription($updatedUser->getDescription() === "" ? 'Je n\'ai pas de description' : $updatedUser->getDescription());
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

        // si il n'y a pas d'erreur, on enregistre l'objet User en base de données
        $this->userRepository->add($loggedUser, true);

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "User modified successfully"], Response::HTTP_OK);
    }
}
