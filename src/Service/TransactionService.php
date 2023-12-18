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

    private $mailer;

    public function __construct(ProductRepository $productRepository, AdRepository $adRepository, MyMailer $mailer)
    {
        $this->productRepository = $productRepository;
        $this->adRepository = $adRepository;
        $this->mailer = $mailer;
    }

    /**
     * Set the user of the product to the buyer and remove the ad
     *
     * @param [type] $content
     * @param [type] $user
     * @return JsonResponse
     */
    public function transaction($content, $user): JsonResponse
    {
        // je récupère le produit grâce à l'id renseigné
        $product = $this->productRepository->findProductByAdId($content);
        // je vérifie que le produit existe
        if (!$product) {
            return new JsonResponse(['message' => 'Produit non trouvé'], Response::HTTP_NOT_FOUND);
        }
        // je modifie le user du produit
        $product[0]->setUser($user);
        // je désassocie l'annonce du produit
        $product[0]->setAd(null);
        // je supprime l'annonce
        $this->adRepository->remove($this->adRepository->find($content), true);
        // j'ajoute le produit dans la base de données
        $this->productRepository->add($product[0], true);
        // je retourne un message de confirmation
        return new JsonResponse(['message' => 'Transaction effectuée avec succès !'], Response::HTTP_OK);
    }

    /**
     * Send a confirmation mail to the buyer
     *
     * @param [type] $buyer
     * @param [type] $product
     * @return void
     */
    public function purchaseConfirmed($buyer, $product)
    {
        $this->mailer->sendTransaction(
            $buyer->getEmail(),
            'Achat confirmé - Produit : ' . $product[0]->getId() . '-' . $product[0]->getTitle(),
            'Votre achat a bien été confirmé !'
        );
    }
}
