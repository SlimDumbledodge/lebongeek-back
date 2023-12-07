<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    /**
     * Get all data from Address entity
     * 
     * @IsGranted("ROLE_ADMIN")
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
     * @Security("is_granted('ROLE_USER') and user === address.getUser()")
     * @Route("/api/{id}/addresses", name="app_api_addresses_show", methods={"GET"})
     * @param AddressRepository $addressRepository
     * @return JsonResponse
     */
    public function show(Address $address): JsonResponse
    {

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
     * @IsGranted("ROLE_USER")
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
<<<<<<< HEAD
            $address->setUser($user);
            // $address->setNameAddress($address->getNameAddress());
            // $address->setStreetNumber($address->getStreetNumber());
            // $address->setStreet($address->getStreet());
            // $address->setPostalCode($address->getPostalCode());
            // $address->setCity($address->getCity());
            // $address->setCountry($address->getCountry());
            // $address->user->setId($address->user->getUser());
=======
            $address->setUser($this->getUser());
>>>>>>> c00c707dfc20cab3edd42d903772abd1aa31aaf5

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
     * @Security("is_granted('ROLE_USER') and user === address.getUser()")
     * @Route("/api/{id}/addresses", name="app_api_addresses_update", methods={"PUT"})
     * @param Request $request
     * @param AddressRepository $addressRepository
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function update(Request $request, AddressRepository $addressRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator, Address $address): JsonResponse
    {
        // Vérifier si l'utilisateur existe
        if (!$address) {
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
            $address->setNameAddress($updatedAddress->getNameAddress());
            $address->setStreetNumber($updatedAddress->getStreetNumber());
            $address->setStreet($updatedAddress->getStreet());
            $address->setPostalCode($updatedAddress->getPostalCode());
            $address->setCity($updatedAddress->getCity());
            $address->setCountry($updatedAddress->getCountry());
            

            // si il n'y a pas d'erreur, on enregistre l'objet Address en base de données
            $addressRepository->add($address,true);
            
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "Address modified successfully"], Response::HTTP_OK);
    }

    /**
     * Delete data from Address entity
     * 
     * @Security("is_granted('ROLE_USER') and user === address.getUser()")
     * @Route("/api/{id}/addresses", name="app_api_addresses_delete", methods={"DELETE"})
     * @param AddressRepository $addressRepository
     * @return JsonResponse
     */
    public function delete(AddressRepository $addressRepository, Address $address): JsonResponse
    {
        // si l'adresse n'existe pas, on retourne une reponse 404
        if (!$address) {
            return $this->json(["error" => "Address not found"], Response::HTTP_NOT_FOUND);
        }

        $addressRepository->remove($address, true);
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "Address deleted successfully"], Response::HTTP_OK);
    }
}
