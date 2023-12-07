<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

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
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, ProductRepository $productRepository, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {   
        //recupere le contenu de la requette (json)
        $content = $request->getContent();
        //recupere l'utilisateur connecté
        $user = $this->getUser();
        try{
            //deserialise le json en objet
            $product = $serializer->deserialize($content, Product::class, 'json');
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUser($user);
         
        
       

    } catch (NotEncodableValueException $err) {
        // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
        return $this->json(["message" => "JSON invalide"],Response::HTTP_BAD_REQUEST);
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
        $productRepository->add($product, true);

        return $this->json(["message" => "Product created successfully"], Response::HTTP_CREATED);
    }

    /**
     * Edit data in Product entity
     * 
     * @Security("is_granted('ROLE_USER') and user === product.getUser()")
     * @Route("/api/{id}/products", name="app_api_product_update", methods={"PUT"})
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @return void
     */
    public function update(Request $request, ProductRepository $productRepository, SerializerInterface $serializerInterface, ValidatorInterface $validator, Product $product)
    {
        // Vérifier si l'utilisateur existe
        if (!$product) {
            return $this->json(["error" => "Product not found"], Response::HTTP_NOT_FOUND);
        }
        //recupere le contenu de la requette (json)
        $content = $request->getContent();
        try {
            // converti le contenu de la requette en objet User
            $updatedProduct = $serializerInterface->deserialize($content, Product::class, 'json');
            $updatedProduct->setUpdatedAt(new \DateTimeImmutable());
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return $this->json(["message" => "JSON invalide"],Response::HTTP_BAD_REQUEST);
        }

        // valide l'objet User (permet de vérifier les assert de l'entité)
        $errors = $validator->validate($updatedProduct);
        // si il y a des erreurs, on les retourne
        if (count($errors) > 0) {
            $dataErrors = [];
        foreach($errors as $error){
            // ici je met le nom du champs en index et le message d'erreur en valeur
            $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
        }
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }  

        // Mettre à jour les propriétés de l'utilisateur existant avec les nouvelles données
        $product->setTitle($updatedProduct->getTitle());
        $product->setPicture($updatedProduct->getPicture());
        $product->setYear($updatedProduct->getYear());
        $product->setSerieNumber($updatedProduct->getSerieNumber());
        $product->setCategory($updatedProduct->getCategory());
        $product->setUpdatedAt(new \DateTimeImmutable());

        // si il n'y a pas d'erreur, on enregistre l'objet User en base de données
        $productRepository->add($product,true);

        // si tout s'est bien passé, on retourne une reponse 200
        return $this->json(["message" => "Product modified successfully"], Response::HTTP_OK);
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
}

