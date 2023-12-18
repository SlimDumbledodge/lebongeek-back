<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * Get all data from Product entity
     * 
     * @Route("/api/products", name="app_api_product", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function list(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        return $this->json($products, Response::HTTP_OK, [], ["groups" => "products"]);
    }

    /**
     * Get data from Product entity
     * 
     * @Route("/api/{id}/products", name="app_api_product_show", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        if (!$product) {
            return $this->json([
                "error" => "Product not found"
            ], Response::HTTP_NOT_FOUND);
        }
        return $this->json($product, Response::HTTP_OK, [], ["groups" => "products"]);
    }

    /**
     * Create new data in Product entity
     * 
     * @IsGranted("ROLE_USER")
     * @Route("/api/products", name="app_api_product_new", methods={"POST"})
     *
     * @param Request $request
     * @param ProductService $productService
     * @return void
     */
    public function create(Request $request, ProductService $productService)
    {
        // j'utilise la method add de mon ProductService pour lui fournir le contenu reçu par la requête et l'utilisateur connecté
        return $productService->add($request->getContent(), $this->getUser());
    }

    /**
     * Edit data in Product entity
     * 
     * @Security("is_granted('ROLE_USER') and user === product.getUser()")
     * @Route("/api/{id}/products", name="app_api_product_update", methods={"PUT"})
     *
     * @param Request $request
     * @param Product $product
     * @param ProductService $productService
     * @return void
     */
    public function update(Request $request, Product $product, ProductService $productService)
    {
        // j'utilise la method edit de mon ProductService pour lui fournir le contenu reçu par la requête et le product à modifier
        return $productService->edit($request->getContent(), $product);
    }

    /**
     * Delete data from Product entity
     * 
     * @Security("is_granted('ROLE_USER') and user === product.getUser()")
     * @Route("/api/{id}/products", name="app_api_product_delete", methods={"DELETE"})
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function delete(ProductRepository $productRepository, Product $product): JsonResponse
    {
        // Vérifier si l'utilisateur existe
        if (!$product) {
            return $this->json(["error" => "Product not found"], Response::HTTP_NOT_FOUND);
        }

        $productRepository->remove($product, true);

        return $this->json(["message" => "Product deleted successfully"], Response::HTTP_OK);
    }

    /**
     * @Route("/api/test", name="app_api_test", methods={"POST"})
     */
    public function test(Request $request)
    {
        $content = $request->getContent();
        // dd($content);
        return $this->json(["message" => $content]);
    }
}
