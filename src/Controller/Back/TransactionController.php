<?php

namespace App\Controller\Back;

use App\Repository\AdRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/transaction/confirmation/{token}/{buyer}", name="app_transaction_confirmation", methods={"GET"})
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param AdRepository $adRepository
     * @return JsonResponse
     */
    public function transactionConfirmation(Request $request, ProductRepository $productRepository, AdRepository $adRepository): JsonResponse
    {
        dd($request->get('buyer'));
        if ($request->get('token') === preg_replace('/[^A-Za-z0-9]/', '-', $this->getUser()->getPassword())) {
        }
    }
}
