<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="app_api_product", methods={"GET"})
     */
    public function list(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        return $this->json($products, Response::HTTP_OK, [], ["groups" => "products"]);
    }



    /**
     * @param ProductRepository $productRepository
     * @param integer $id
     * @return JsonResponse
     * @Route("/api/{id}/products", name="app_api_product_show", methods={"GET"})
     */
    public function show(ProductRepository $productRepository, int $id): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json([
                "error" => "Product not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($product, Response::HTTP_OK, [], ["groups" => "products"]);
    }


    /**
     * @return JsonResponse
     * @Route("/api/products", name="app_api_product_new", methods={"POST"})
     */
    public function create(Request $request, ProductRepository $productRepository, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        //recupere le contenu de la requette (json)
        $content = $request->getContent();
        try{
        //deserialise le json en objet
        $product = $serializer->deserialize($content, Product::class, 'json');
        $product->setTitle($product->getTitle());
        $product->setPicture($product->getPicture());
        $product->setYear($product->getYear());
        $product->setYear($product->getYear());
        $product->setCategory($product->getCategory());


    }catch(\Exception $e){
        return $this->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
    //valide l'objet
    $errors = $validator->validate($product);
    //si il y a des erreurs
    if(count($errors) > 0){
        $dataErrors = [];
        //on boucle sur les erreurs
        foreach($errors as $error){
            $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
        }
        //on retourne les erreurs
        return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    $productRepository->save($product, true);

    return $this->json(["message" => "Product created successfully"], Response::HTTP_CREATED);
    }

    public function update(){

    }
}

