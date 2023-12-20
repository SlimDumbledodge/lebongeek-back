<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductService
{
    private $serializer;

    private $validator;

    private $productRepository;

    private $categoriesRepository;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, ProductRepository $productRepository, CategoryRepository $categoriesRepository)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->productRepository = $productRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    /**
     * Create new data in Product entity
     *
     * @param [type] $content
     * @param User $user
     * @return JsonResponse
     */
    public function add($content, $user): JsonResponse
    {
        try {
            // je décode la saisie
            $jsonData = json_decode($content, true);
            //deserialise le json en objet
            $product = $this->serializer->deserialize($content, Product::class, 'json');

            // je vérifie que la categorie est bien renseignée
            if (!empty($jsonData['category']['id'])) {
                // je récupère une catégorie grâce à l'id renseigné
                $categoryId = $this->categoriesRepository->find($jsonData['category']['id']);
                // j'assigne la catégorie au produit
                $product->setCategory($categoryId);
            } else {
                // Si l'id de la catégorie n'est pas renseigné, alors je renvoie une erreur 400
                return new JsonResponse(["message" => "Veuillez associer votre produit à une catégorie"], Response::HTTP_BAD_REQUEST);
            }

            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUser($user);
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json

            return new JsonResponse(["message" => "JSON invalide"], Response::HTTP_BAD_REQUEST);
        }

        //valide l'objet
        $errors = $this->validator->validate($product);
        //si il y a des erreurs
        if (count($errors) > 0) {
            $dataErrors = [];
            //on boucle sur les erreurs
            foreach ($errors as $error) {
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            //on retourne les erreurs
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->productRepository->add($product, true);

        return new JsonResponse(["message" => "Product created successfully", "productId" => $product->getId()], Response::HTTP_CREATED);
    }

    /**
     * Edit data in Product entity
     *
     * @param [type] $content
     * @param Product $product
     * @param User $user
     * @return JsonResponse
     */
    public function edit($content, $product): JsonResponse
    {
        // Vérifier si l'utilisateur existe
        if (!$product) {
            return new JsonResponse(["error" => "Product not found"], Response::HTTP_NOT_FOUND);
        }

        // je décode la saisie
        $jsonData = json_decode($content, true);

        try {
            // converti le contenu de la requette en objet User
            $updatedProduct = $this->serializer->deserialize($content, Product::class, 'json');

            // je vérifie que la categorie est bien renseignée
            if (!empty($jsonData['category']['id'])) {
                // je récupère une catégorie grâce à l'id renseigné
                $categoryId = $this->categoriesRepository->find($jsonData['category']['id']);
                // j'assigne la catégorie au produit
                $product->setCategory($categoryId);
            } else {
                // Si l'id de la catégorie n'est pas renseigné, alors je renvoie une erreur 400
                return new JsonResponse(["message" => "Veuillez associer votre produit à une catégorie"], Response::HTTP_BAD_REQUEST);
            }

            $updatedProduct->setUpdatedAt(new \DateTimeImmutable());
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return new JsonResponse(["message" => "JSON invalide"], Response::HTTP_BAD_REQUEST);
        }

        // valide l'objet User (permet de vérifier les assert de l'entité)
        $errors = $this->validator->validate($updatedProduct);
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
        $product->setTitle($updatedProduct->getTitle());
        $product->setPicture($updatedProduct->getPicture());
        $product->setYear($updatedProduct->getYear());
        $product->setSerialNumber($updatedProduct->getSerialNumber());
        $product->setUpdatedAt(new \DateTimeImmutable());

        // si il n'y a pas d'erreur, on enregistre l'objet User en base de données
        $this->productRepository->add($product, true);

        // si tout s'est bien passé, on retourne une reponse 200
        return new JsonResponse(["message" => "Product modified successfully"], Response::HTTP_OK);
    }
}
