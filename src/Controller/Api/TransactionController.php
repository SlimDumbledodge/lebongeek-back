<?php

namespace App\Controller\Api;

use App\Service\MyMailer;
use App\Repository\AdRepository;
use App\Service\TransactionService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
    /**
     * @Route("/api/transaction", name="app_api_transaction", methods={"POST"})
     */
    public function transaction(Request $request, TransactionService $transactionService): JsonResponse
    {
        // je décode la saisie
        $content = json_decode($request->getContent(), true);

        return $transactionService->transaction($content, $this->getUser());
    }

    /**
     * @Route("/api/transaction/confirm", name="app_api_transaction_confirm", methods={"POST"})
     * @param MyMailer $mailer
     * @return void
     */
    public function confirmationMail(Request $request, MyMailer $mailer, ProductRepository $productRepository, AdRepository $adRepository)
    {
        $content = json_decode($request->getContent(), true);
        // je récupère le produit grâce à l'id renseigné
        $product = $productRepository->findProductByAdId($content['ad']);

        $mailer->sendTransaction(
            $this->getUser()->getEmail(),
            $product[0]->getUser()->getEmail(),
            'Confirmation de transaction - Produit : ' . $product[0]->getId() . '-' . $product[0]->getTitle(),
            $this->generateUrl('app_transaction_confirmation', ['token' => preg_replace('/[^A-Za-z0-9]/', '-', $product[0]->getUser()->getPassword()), 'buyer' => $this->getUser()->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        return $this->json(['message' => 'Mail envoyé !'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/transaction/confirmed", name="app_api_transaction_confirmed", methods={"GET"})
     */
    public function transactionConfirmed(Request $request, ProductRepository $productRepository, AdRepository $adRepository): JsonResponse
    {
        // je décode la saisie
        $content = json_decode($request->getContent(), true);
        // je récupère le produit grâce à l'id renseigné
        $product = $productRepository->findProductByAdId($content['ad']);
        // je modifie le user du produit
        $product[0]->setUser($this->getUser());
        // je désassocie l'annonce du produit
        $product[0]->setAd(null);
        // je supprime l'annonce
        $adRepository->remove($adRepository->find($content['ad']), true);
        // j'ajoute le produit dans la base de données
        $productRepository->add($product[0], true);
        // je retourne un message de confirmation
        return $this->json(['message' => 'Transaction effectuée avec succès !'], Response::HTTP_OK);
    }
}
