<?php

namespace App\Controller\Api;

use App\Entity\Ad;
use App\Service\AdService;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * Get all data from Ad entity
     * 
     * @Route("/api/ads", name="app_api_ads", methods={"GET"})
     * @param AdRepository $adRepository
     * @return JsonResponse
     */
    public function list(AdRepository $adRepository): JsonResponse
    {
        $ads = $adRepository->findAll();

        return $this->json($ads, Response::HTTP_OK, [], ["groups" => "ads"]);
    }

    /**
     * Get data from Ad entity
     * 
     * @Route("/api/{id}/ads", name="app_api_ads_show", methods={"GET"})
     * @param AdRepository $adRepository
     * @return JsonResponse
     */
    public function show(Ad $ad): JsonResponse
    {
        if (!$ad) {
            return $this->json([
                "error" => "Ad not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($ad, Response::HTTP_OK, [], ["groups" => "ads"]);
    }

    /**
     * Create new data in Ad entity
     * 
     * @IsGranted("ROLE_USER")
     * @Route("/api/ads", name="app_api_ads_new", methods={"POST"})
     *
     * @param Request $request
     * @param AdService $adService
     * @return JsonResponse
     */
    public function create(Request $request, AdService $adService): JsonResponse
    {
        // j'utilise la method add de mon AdService pour lui fournir le contenu reçu par la requête et l'utilisateur connecté
        return $adService->add($request->getContent(), $this->getUser());
    }

    /**
     * Edit data in Ad entity
     * 
     * @Security("is_granted('ROLE_USER') and user === ad.getUser()")
     * @Route("/api/{id}/ads", name="app_api_ads_update", methods={"PUT"})
     *
     * @param Request $request
     * @param Ad $ad
     * @param AdService $adService
     * @return JsonResponse
     */
    public function update(Request $request, Ad $ad, AdService $adService): JsonResponse
    {
        // j'utilise la method edit de mon AdService pour lui fournir le contenu reçu par la requête et l'annonce à modifier
        return $adService->edit($ad, $request->getContent());
    }

    /**
     * Delete data from Ad entity
     * 
     * @Security("is_granted('ROLE_USER') and user === ad.getUser()")
     * @Route("/api/{id}/ads", name="app_api_ads_delete", methods={"DELETE"})
     * @param AdRepository $adRepository
     * @return JsonResponse
     */
    public function delete(AdRepository $adRepository, Ad $ad): JsonResponse
    {
        // si l'utilisateur n'existe pas, on retourne une reponse 404
        if (!$ad) {
            return $this->json(["error" => "ad not found"], Response::HTTP_NOT_FOUND);
        }

        $adRepository->remove($ad, true);
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "ad deleted successfully"], Response::HTTP_OK);
    }
}
