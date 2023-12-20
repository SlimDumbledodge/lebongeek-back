<?php

namespace App\Service;

use App\Entity\Ad;
use App\Repository\AdRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdService
{
    private $serializer;

    private $validator;

    private $adRepository;

    private $productRepository;

    private $categoriesRepository;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, AdRepository $adRepository, ProductRepository $productRepository, CategoryRepository $categoriesRepository)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->adRepository = $adRepository;
        $this->productRepository = $productRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    /**
     * Create new data in Ad entity
     *
     * @param [type] $content
     * @param [type] $user
     * @return JsonResponse
     */
    public function add($content, $user): JsonResponse
    {
        try {
            // je décode la saisie
            $jsonData = json_decode($content, true);
            // converti le contenu de la requette en objet ad
            $ad = $this->serializer->deserialize($content, Ad::class, 'json');
        } catch (\Exception $e) {
            // si il y a une erreur, on retourne une reponse 400 avec le message d'erreur
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        // valide l'objet ad permet de vérifier les assert de l'entité
        $errors = $this->validator->validate($ad);
        // si il y a des erreurs, on les retourne
        if (count($errors) > 0) {
            $dataErrors = [];
            foreach ($errors as $error) {
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // j'assigne l'utilisateur à l'annonce
        $ad->setUser($user);
        // j'assigne la date de création à l'annonce
        $ad->setCreatedAt(new \DateTimeImmutable());

        // je vérifie que le produit à mettre en vente est bien renseigné
        if (!empty($jsonData['productId'])) {
            // je récupère le produit à mettre en vente
            $product = $this->productRepository->find($jsonData['productId']);
            // je lui attribut la catégorie de l'annonce
            $ad->setCategory($product->getCategory());
            // je vérifie que le produit n'est pas déjà associé à une annonce
            if (!empty($product->getAd())) {
                // si le produit est déjà associé à une annonce, alors je renvoie une erreur 400
                return new JsonResponse(["message" => "Ce produit est déjà associé à une annonce"], Response::HTTP_BAD_REQUEST);
            }
            // si il n'y a pas d'erreur, on enregistre l'objet ad en base de données
            $this->adRepository->add($ad, true);
            //  je lui attribut l'id de l'annonce
            $product->setAd($ad);
            // puis j'envoie en bdd
            $this->productRepository->add($product, true);
        } else {
            // si aucun produit n'est associé à l'annonce, alors je renvoie une erreur 400
            return new JsonResponse(["message" => "Veuillez associer un produit à l'annonce"], Response::HTTP_BAD_REQUEST);
        }

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "ad created successfully"], Response::HTTP_CREATED);
    }


    /**
     * Edit data in Ad entity
     *
     * @param [type] $ad
     * @param [type] $content
     * @return JsonResponse
     */
    public function edit($ad, $content): JsonResponse
    {
        // Vérifier si l'article existe
        if (!$ad) {
            return new JsonResponse(["error" => "Ad not found"], Response::HTTP_NOT_FOUND);
        }

        // je décode la saisie
        $jsonData = json_decode($content, true);

        try {
            // converti le contenu de la requette en objet ad
            $updatedAd = $this->serializer->deserialize($content, Ad::class, 'json');

            // je vérifie que le produit à mettre en vente est bien renseigné
            if (!empty($jsonData['productId'])) {
                // je récupère le produit à mettre en vente
                $product = $this->productRepository->find($jsonData['productId']);
                // je lui attribut la catégorie de l'annonce
                $ad->setCategory($product->getCategory());
            } else {
                // Si l'id de la catégorie n'est pas renseigné, alors je renvoie une erreur 400
                return new JsonResponse(["message" => "Veuillez associer votre produit à une catégorie"], Response::HTTP_BAD_REQUEST);
            }
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return new JsonResponse(["message" => "JSON invalide"], Response::HTTP_BAD_REQUEST);
        }
        // valide l'objet Ad (permet de vérifier les assert de l'entité)
        $errors = $this->validator->validate($updatedAd);
        // si il y a des erreurs, on les retourne
        if (count($errors) > 0) {
            $dataErrors = [];
            foreach ($errors as $error) {
                // ici je met le nom du champs en index et le message d'erreur en valeur
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Mettre à jour les propriétés de l'article existant avec les nouvelles données
        $ad->setTitle($updatedAd->getTitle());
        $ad->setDescription($updatedAd->getDescription());
        $ad->setPrice($updatedAd->getPrice());
        $ad->setState($updatedAd->getState());
        $ad->setLocation($updatedAd->getLocation());
        $ad->setUpdatedAt(new \DateTimeImmutable());

        // si il n'y a pas d'erreur, on enregistre l'objet Ad en base de données
        $this->adRepository->add($ad, true);

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "Ad modified successfully"], Response::HTTP_OK);
    }
}
