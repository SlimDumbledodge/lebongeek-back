<?php

namespace App\Service;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressService
{
    private $serializer;

    private $validator;

    private $addressRepository;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, AddressRepository $addressRepository)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Create new data in Address entity
     *
     * @param [type] $user
     * @param [type] $content
     * @return JsonResponse
     */
    public function add($user, $content): JsonResponse
    {
        try {
            // converti le contenu de la requette en objet address
            $address = $this->serializer->deserialize($content, Address::class, 'json');
            $address->setUser($user);
        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        // valide l'objet address (permet de vérifier les assert de l'entité)
        $errors = $this->validator->validate($address);
        // si il y a des erreurs, on les retourne
        if (count($errors) > 0) {
            $dataErrors = [];
            foreach ($errors as $error) {
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // si il n'y a pas d'erreur, on enregistre l'objet address en base de données
        $this->addressRepository->add($address, true);

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "address created successfully"], Response::HTTP_CREATED);
    }

    /**
     * Edit data in Address entity
     *
     * @param [type] $address
     * @param [type] $content
     * @return JsonResponse
     */
    public function edit($address, $content): JsonResponse
    {
        // Vérifier si l'utilisateur existe
        if (!$address) {
            return new JsonResponse(["error" => "Address not found"], Response::HTTP_NOT_FOUND);
        }

        try {
            // converti le contenu de la requette en objet Address
            $updatedAddress = $this->serializer->deserialize($content, Address::class, 'json');
        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        // valide l'objet Address (permet de vérifier les assert de l'entité)
        $errors = $this->validator->validate($updatedAddress);
        // si il y a des erreurs, on les retourne
        if (count($errors) > 0) {
            $dataErrors = [];
            foreach ($errors as $error) {
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Mettre à jour les propriétés de l'adresse existant avec les nouvelles données
        $address->setNameAddress($updatedAddress->getNameAddress());
        $address->setStreetNumber($updatedAddress->getStreetNumber());
        $address->setStreet($updatedAddress->getStreet());
        $address->setPostalCode($updatedAddress->getPostalCode());
        $address->setCity($updatedAddress->getCity());
        $address->setCountry($updatedAddress->getCountry());

        // si il n'y a pas d'erreur, on enregistre l'objet Address en base de données
        $this->addressRepository->add($address, true);

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "Address modified successfully"], Response::HTTP_OK);
    }
}
