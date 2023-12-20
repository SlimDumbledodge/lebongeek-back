<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Service\ProductService;
use App\Service\UploaderService;
use App\Repository\ProductRepository;
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
     * @param UploaderService $uploaderService
     * @return void
     */
    public function create(Request $request, ProductService $productService, UploaderService $uploaderService)
    {
        // Vérifier si l'utilisateur à renseigné une image
        if ($request->files->get('picture')) {
            // Récupérez le fichier téléchargé
            $getPicture = $request->files->get('picture');
            // j'utilise la method upload de mon UploaderService pour lui fournir le contenu reçu par la requête et le dossier de destination
            $picture = $uploaderService->upload($getPicture, 'images/product/', 'product/');
        }
        // j'utilise la method add de mon ProductService pour lui fournir le contenu reçu par la requête et l'utilisateur connecté
        return $productService->add($request->getContent(), $this->getUser(), $picture ?? null);
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
            // Retourner un message d'erreur si le produit n'existe pas
            return $this->json(["error" => "Product not found"], Response::HTTP_NOT_FOUND);
        }
        // Supprimer le produit
        $productRepository->remove($product, true);
        // Retourner un message de succès
        return $this->json(["message" => "Product deleted successfully"], Response::HTTP_OK);
    }

    /**
     * @Route("/api/test", name="app_api_test", methods={"POST"})
     */
    public function test(Request $request, UploaderService $uploaderService)
    {
        // Récupérez le fichier téléchargé
        $content = $request->files->get('picture');

        return $uploaderService->upload($content, 'images/product/', 'product/');
    }

    /**
     * @Route("/api/unlink", name="app_api_unlink", methods={"DELETE"})
     *
     * @return void
     */
    public function unlink(ProductRepository $productRepository, UploaderService $unploaderService)
    {
        // Récupérez le produit
        $product = $productRepository->find(83);
        // Récupérez le nom de l'image
        $picture = $product->getPicture();
        // Vérifier si l'image existe
        if ($picture) {
            // Supprimer l'image
            $unploaderService->deletePicture('images/product/', $picture);
            // Supprimer le nom de l'image dans la base de données
            $product->setPicture(null);
            // Mettre à jour le produit
            $productRepository->add($product, true);
            // Retourner un message de succès
            return $this->json(["message" => "File deleted successfully"], Response::HTTP_OK);
        }
    }
       
}
