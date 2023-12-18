<?php

namespace App\Controller\Back;

use App\Repository\AdRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\TransactionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/transaction/confirmation/{token}/{buyer}/{ad}", name="app_transaction_confirmation", methods={"GET"})
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param AdRepository $adRepository
     * @return Response
     */
    public function transactionConfirmation(Request $request, UserRepository $userRepository, AdRepository $adRepository, ProductRepository $productRepository, TransactionService $transactionService): Response
    {
        // je récupère le token de l'utilisateur
        $userToken = preg_replace('/[^A-Za-z0-9]/', '-', $this->getUser()->getPassword());
        // je récupère l'acheteur
        $buyer = $userRepository->find($request->get('buyer'));
        // je récupère l'annonce
        $ad = $adRepository->find($request->get('ad'));
        // je récupère le produit
        $product = $productRepository->findProductByAdId($request->get('ad'));
        // si le vendeur est bien l'utilisateur
        if ($request->get('token') === $userToken) {
            // si l'annonce existe et que l'annonce appartient bien à l'utilisateur
            if (!empty($ad) && $ad->getUser() === $this->getUser()) {
                // si l'acheteur existe
                if (!empty($buyer)) {
                    // je lance la transaction
                    $transactionService->transaction($request->get('ad'), $buyer);
                    // j'envoie un mail de confirmation à l'acheteur
                    $transactionService->purchaseConfirmed($buyer, $product);
                    // je retourne un message de confirmation
                    return $this->render('back/transaction/confirmation.html.twig');
                }
            }
        }
        // si l'utilisateur n'est pas le vendeur ou que l'annonce n'existe pas
        // return new Response('Accès interdit.', Response::HTTP_FORBIDDEN);
        return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
    }
}
