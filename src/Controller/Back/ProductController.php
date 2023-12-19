<?php

namespace App\Controller\Back;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    /**
     * @Route("/back/product", name="back_product")
     *
     * @return void
     */
    public function index(Request $request, ProductRepository $productRepository)
    {

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($product);
            $product->setCreatedAt(new \DateTimeImmutable());
            $productRepository->add($product, true);
        }
        return $this->renderForm('back/product/index.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/back/product/{id}", name="back_product_show")
     *
     * @param Product $product
     * @return void
     */
    public function show(Product $product, ProductRepository $productRepository)
    {
        $productData = $productRepository->find($product);
        // dd($productData);
        return $this->render('back/product/show.html.twig', [
            'product' => $productData
        ]);
    }
}
