<?php

namespace App\Service;

use App\Repository\AdRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransactionService
{

    private $productRepository;

    private $adRepository;

    public function __construct(ProductRepository $productRepository, AdRepository $adRepository)
    {
        $this->productRepository = $productRepository;
        $this->adRepository = $adRepository;
    }


    public function transaction($content, $user): JsonResponse
    {
        // je récupère le produit grâce à l'id renseigné
        $product = $this->productRepository->findProductByAdId($content['ad']);
        // je modifie le user du produit
        $product[0]->setUser($user);
        // je désassocie l'annonce du produit
        $product[0]->setAd(null);
        // je supprime l'annonce
        $this->adRepository->remove($this->adRepository->find($content['ad']), true);
        // j'ajoute le produit dans la base de données
        $this->productRepository->add($product[0], true);
        // je retourne un message de confirmation
        return new JsonResponse(['message' => 'Transaction effectuée avec succès !'], Response::HTTP_OK);
    }
}
