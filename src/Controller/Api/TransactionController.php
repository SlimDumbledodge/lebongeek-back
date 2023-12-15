<?php

namespace App\Controller\Api;

use App\Repository\AdRepository;
use App\Repository\ProductRepository;
use App\Service\MyMailer;
use App\Service\TransactionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
            'Confirmation de transaction - Produit : ' . dd($product[0]->getTitle()),
            $this->generateUrl('app_api_transaction_confirmation', ['token' => str_replace('/', '-', $product[0]->getUser()->getPassword()), 'buyer' => $this->getUser()->getId()])
        );

        return $this->json(['message' => 'Mail envoyé !'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/transaction/confirmation/{token}/{buyer}", name="app_api_transaction_confirmation", methods={"GET"})
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param AdRepository $adRepository
     * @return JsonResponse
     */
    public function transactionConfirmation(Request $request, ProductRepository $productRepository, AdRepository $adRepository): JsonResponse
    {
        // dd($request);
        // if ($request->request->token === str_replace('/', '-', $this->getUser()->getPassword())) {
        // }
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
