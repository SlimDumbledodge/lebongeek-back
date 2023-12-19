<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ProductService;
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
    public function test(Request $request, ProductRepository $productRepository)
    {
        /* $content = $request->getContent(); */
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... (votre code existant)
            
            // Récupérer l'objet UploadedFile
            /** @var UploadedFile $imageFile */
            $imageFile = $form['imageFile']->getData();
            
            // Obtenez le répertoire cible depuis la configuration VichUploader
            $targetDirectory = $this->getParameter('vich_uploader.mappings.product.imageFile.upload_destination');
            
            // Générez un nom de fichier unique
            $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
            
            // Déplacez le fichier vers le répertoire cible
            $imageFile->move($targetDirectory, $fileName);
            
            // Mettez à jour l'entité Product avec le nouveau nom de fichier
            $product->setImageName($fileName);
            
            // Enregistrez l'entité dans la base de données
            $productRepository->add($product, true);
        }
        dd($product);
        return $this->json(["message" => $product]);
    }
       
}
