<?php

namespace App\Controller\Api;

use App\Service\MyMailer;
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
     * Generate a confirmation mail for the seller
     * 
     * @Route("/api/transaction/confirm", name="app_api_transaction_confirm", methods={"POST"})
     *
     * @param Request $request
     * @param MyMailer $mailer
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function confirmationMail(Request $request, MyMailer $mailer, ProductRepository $productRepository): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        // je récupère le produit grâce à l'id renseigné
        $product = $productRepository->findProductByAdId($content['ad']);

        $mailer->sendTransaction(
            // le mail du vendeur
            $product[0]->getUser()->getEmail(),
            'Confirmation de transaction - Produit : ' . $product[0]->getId() . '-' . $product[0]->getTitle(),
            // génération de l'url de confirmation avec le token du vendeur, l'id de l'acheteur et l'id de l'annonce
            $this->generateUrl(
                'app_transaction_confirmation',
                [
                    'token' => preg_replace('/[^A-Za-z0-9]/', '-', $product[0]->getUser()->getPassword()),
                    'buyer' => $this->getUser()->getId(),
                    'ad' => $content['ad']
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );

        return $this->json(['message' => 'Mail envoyé !'], Response::HTTP_OK);
    }
}
