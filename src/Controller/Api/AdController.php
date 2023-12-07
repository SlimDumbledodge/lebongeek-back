<?php

namespace App\Controller\Api;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

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
     * @param Request $request
     * @param AdRepository $adRepository
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, AdRepository $adRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator): JsonResponse
    {
        //recupere le contenu de la requette (json)
        $content = $request->getContent();
        $user = $this->getUser();
        try {
            // converti le contenu de la requette en objet ad
            $ad = $serializerInterface->deserialize($content, Ad::class, 'json');
            $ad->setCreatedAt(new \DateTimeImmutable());
            $ad->setUser($user);
            
        

        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return $this->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
            // valide l'objet ad permet de vérifier les assert de l'entité
            $errors = $validator->validate($ad);
            // si il y a des erreurs, on les retourne
            if (count($errors) > 0) {
                $dataErrors = [];
            foreach($errors as $error){
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
                return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            // si il n'y a pas d'erreur, on enregistre l'objet ad en base de données
            $adRepository->add($ad,true);
            
        
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "ad created successfully"], Response::HTTP_CREATED);
    }

    /**
     * Edit data in Ad entity
     * 
     * @Security("is_granted('ROLE_USER') and user === ad.getUser()")
     * @Route("/api/{id}/ads", name="app_api_ads_update", methods={"PUT"})
     * @param Request $request
     * @param AdRepository $adRepository
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function update(Request $request, AdRepository $adRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator, Ad $ad): JsonResponse
    {
        // Vérifier si l'article existe
        if (!$ad) {
            return $this->json(["error" => "Ad not found"], Response::HTTP_NOT_FOUND);
        }

        //recupere le contenu de la requette (json)
        $content = $request->getContent();

        try {
            // converti le contenu de la requette en objet ad
            $updatedAd = $serializerInterface->deserialize($content, Ad::class, 'json');
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return $this->json(["message" => "JSON invalide"],Response::HTTP_BAD_REQUEST);
        }
            // valide l'objet Ad (permet de vérifier les assert de l'entité)
            $errors = $validator->validate($updatedAd);
            // si il y a des erreurs, on les retourne
            if (count($errors) > 0) {
                $dataErrors = [];
            foreach($errors as $error){
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
                return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }  

            // Mettre à jour les propriétés de l'article existant avec les nouvelles données
            $ad->setDescription($updatedAd->getDescription());
            $ad->setPrice($updatedAd->getPrice());
            $ad->setState($updatedAd->getState());
            $ad->setLocation($updatedAd->getLocation());
            $ad->setUpdatedAt(new \DateTimeImmutable());            

            // si il n'y a pas d'erreur, on enregistre l'objet Ad en base de données
            $adRepository->add($ad,true);
            
        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "Ad modified successfully"], Response::HTTP_OK);
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
