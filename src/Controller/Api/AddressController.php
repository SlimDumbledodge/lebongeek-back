<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Repository\AddressRepository;
use App\Service\AddressService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     *
     * @param Request $request
     * @param AddressService $addressService
     * @return JsonResponse
     */
    public function create(Request $request, AddressService $addressService): JsonResponse
    {
        // j'utilise la method add de mon AddressService pour lui fournir le contenu reçu par la requête et l'utilisateur connecté
        return $addressService->add($this->getUser(), $request->getContent());
    }

    /**
     * Edit data in Address entity
     * 
     * @Security("is_granted('ROLE_USER') and user === address.getUser()")
     * @Route("/api/{id}/addresses", name="app_api_addresses_update", methods={"PUT"})
     *
     * @param Request $request
     * @param AddressService $addressService
     * @param Address $address
     * @return JsonResponse
     */
    public function update(Request $request, AddressService $addressService, Address $address): JsonResponse
    {
        // j'utilise la method edit de mon AddressService pour lui fournir le contenu reçu par la requête et le adresse à modifier
        return $addressService->edit($address, $request->getContent());
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
