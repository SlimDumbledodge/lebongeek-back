<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressController extends AbstractController
{
    /**
     * Get all data from Address entity
     * 
     * @Route("/api/addresses", name="app_api_addresses", methods={"GET"})
     * @param AddressRepository $addressRepository
     * @return JsonResponse
     */
    public function list(AddressRepository $addressRepository): JsonResponse
    {
        $addresses = $addressRepository->findAll();
        
        return $this->json($addresses, Response::HTTP_OK, [], ["groups" => "address"]);
    }

    /**
     * Get data from Address entity
     * 
     * @Route("/api/{id}/addresses", name="app_api_addresses_show", methods={"GET"})
     * @param AddressRepository $addressRepository
     * @param integer $id
     * @return JsonResponse
     */
    public function show(AddressRepository $addressRepository, int $id): JsonResponse
    {
        $address = $addressRepository->find($id);

        if (!$address) {
            return $this->json([
                "error" => "Address not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($address, Response::HTTP_OK, [], ["groups" => "address"]);
    }

    /**
     * Create new data in Address entity
     * 
     * @Route("/api/addresses", name="app_api_addresses_new", methods={"POST"})
     * @param Request $request
     * @param AddressRepository $addressRepository
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, AddressRepository $addressRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator): JsonResponse
    {
        //recupere le contenu de la requette (json)
        $content = $request->getContent();
        $user = $this->getUser();
        try {
            // converti le contenu de la requette en objet address
            $address = $serializerInterface->deserialize($content, Address::class, 'json');
            $address->setUser($user);
            // $address->setNameAddress($address->getNameAddress());
            // $address->setStreetNumber($address->getStreetNumber());
            // $address->setStreet($address->getStreet());
            // $address->setPostalCode($address->getPostalCode());
            // $address->setCity($address->getCity());
            // $address->setCountry($address->getCountry());
            // $address->user->setId($address->user->getUser());

        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return $this->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
            // valide l'objet address (permet de vérifier les assert de l'entité)
            $errors = $validator->validate($address);
            // si il y a des erreurs, on les retourne
            if (count($errors) > 0) {
                $dataErrors = [];
            foreach($errors as $error){
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
                return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            // si il n'y a pas d'erreur, on enregistre l'objet address en base de données
            $addressRepository->add($address,true);
            
        
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "address created successfully"], Response::HTTP_CREATED);
    }

    /**
     * Edit data in Address entity
     * 
     * @Route("/api/{id}/addresses", name="app_api_addresses_update", methods={"PUT"})
     * @param Request $request
     * @param AddressRepository $addressRepository
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @param integer $id
     * @return JsonResponse
     */
    public function update(Request $request, AddressRepository $addressRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator, int $id): JsonResponse
    {

        // Récupérer l'utilisateur existant par son ID
        $existingAddress = $addressRepository->find($id);
            
        // Vérifier si l'utilisateur existe
        if (!$existingAddress) {
            return $this->json(["error" => "Address not found"], Response::HTTP_NOT_FOUND);
        }

        //recupere le contenu de la requette (json)
        $content = $request->getContent();

        try {
            // converti le contenu de la requette en objet Address
            $updatedAddress = $serializerInterface->deserialize($content, Address::class, 'json');
        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return $this->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
            // valide l'objet Address (permet de vérifier les assert de l'entité)
            $errors = $validator->validate($updatedAddress);
            // si il y a des erreurs, on les retourne
            if (count($errors) > 0) {
                $dataErrors = [];
            foreach($errors as $error){
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
                return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }  

            // Mettre à jour les propriétés de l'adresse existant avec les nouvelles données
            $existingAddress->setNameAddress($updatedAddress->getNameAddress());
            $existingAddress->setStreetNumber($updatedAddress->getStreetNumber());
            $existingAddress->setStreet($updatedAddress->getStreet());
            $existingAddress->setPostalCode($updatedAddress->getPostalCode());
            $existingAddress->setCity($updatedAddress->getCity());
            $existingAddress->setCountry($updatedAddress->getCountry());
            

            // si il n'y a pas d'erreur, on enregistre l'objet Address en base de données
            $addressRepository->add($existingAddress,true);
            
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "Address modified successfully"], Response::HTTP_OK);
    }

    /**
     * Delete data from Address entity
     * 
     * @Route("/api/{id}/addresses", name="app_api_addresses_delete", methods={"DELETE"})
     * @param AddressRepository $addressRepository
     * @param integer $id
     * @return JsonResponse
     */
    public function delete(AddressRepository $addressRepository, int $id): JsonResponse
    {
        $address = $addressRepository->find($id);
        // si l'adresse n'existe pas, on retourne une reponse 404
        if (!$address) {
            return $this->json(["error" => "Address not found"], Response::HTTP_NOT_FOUND);
        }

        $addressRepository->remove($address, true);
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "Address deleted successfully"], Response::HTTP_OK);
    }
}
