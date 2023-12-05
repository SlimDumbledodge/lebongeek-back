<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class CategoryController extends AbstractController
{
    /**
     * @Route("/api/categories", name="app_api_category", methods={"GET"})
     */
    public function list(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->json($categories, Response::HTTP_OK, [], ["groups" => "categories"]);
    }


    /**
     * @param CategoryRepository $categoryRepository
     * @param integer $id
     * @return JsonResponse
     * @Route("/api/{id}/categories", name="app_api_category_show", methods={"GET"})
     */
    public function show(CategoryRepository $categoryRepository, int $id): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->json([
                "error" => "Category not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($category, Response::HTTP_OK, [], ["groups" => "categories"]);
    }



    /**
     * @return JsonResponse
     * @Route("/api/categories", name="app_api_category_create", methods={"POST"})
     */
    public function create(Request $request, CategoryRepository $categoryRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $content = $request->getContent();

        try{
            $categories = $serializer->deserialize($content, Category::class, 'json');

            /* $categories->setName($categories->getName());
            $categories->setImage($categories->getImage()); */

        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return $this->json(["message" => $err . " : JSON invalide"],Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($categories);
        if(count($errors) > 0){
            $dataErrors = [];
            //on boucle sur les erreurs
            foreach($errors as $error){
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            //on retourne les erreurs
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $categoryRepository->add($categories, true);

        return $this->json(["message" => "Category created successfully"], Response::HTTP_CREATED);
    }


    /**
     * @return JsonResponse
     * @Route("/api/{id}/categories", name="app_api_category_update", methods={"PUT"})
     */
    public function update(Request $request, CategoryRepository $categoryRepository, SerializerInterface $serializer, ValidatorInterface $validator, int $id)
    {
        $existingCategory = $categoryRepository->find($id);

        if (!$existingCategory) {
            return $this->json(["error" => "Category not found"], Response::HTTP_NOT_FOUND);
        }

        $content = $request->getContent();

        try{
            $updatedCategory = $serializer->deserialize($content, Category::class, 'json');
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return $this->json(["message" => "JSON invalide"],Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($updatedCategory);
        if(count($errors) > 0){
            $dataErrors = [];
            //on boucle sur les erreurs
            foreach($errors as $error){
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            //on retourne les erreurs
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $existingCategory->setName($updatedCategory->getName());
        $existingCategory->setImage($updatedCategory->getImage());

        $categoryRepository->add($existingCategory, true);

        return $this->json(["message" => "Category updated successfully"], Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     * @Route("/api/{id}/categories", name="app_api_category_delete", methods={"DELETE"})
     */
    public function delete(CategoryRepository $categoryRepository, int $id)
    {
        $existingCategory = $categoryRepository->find($id);

        if (!$existingCategory) {
            return $this->json(["error" => "Category not found"], Response::HTTP_NOT_FOUND);
        }

        $categoryRepository->remove($existingCategory, true);
        return $this->json(["message" => "Category deleted successfully"], Response::HTTP_OK);
    }

}
