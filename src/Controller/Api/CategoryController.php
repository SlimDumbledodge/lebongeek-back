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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class CategoryController extends AbstractController
{

    /**
     * Get all data from Category entity
     * 
     * @Route("/api/list/categories", name="app_api_list_category", methods={"GET"})
     * @param CategoryRepository $categoriesRepository
     * @return void
     */
    public function categoryList(CategoryRepository $categoriesRepository)
    {
        $categories = $categoriesRepository->findAll();
        return $this->json($categories, Response::HTTP_OK, [], ["groups" => "onlyCategories"]);
    }

    /**
     * Get all data from Category entity with Ad and Product
     * 
     * @Route("/api/categories", name="app_api_category", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     */
    public function list(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->json($categories, Response::HTTP_OK, [], ["groups" => "categories"]);
    }

    /**
     * Get data from Category entity
     * 
     * @Route("/api/{id}/categories", name="app_api_category_show", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        if (!$category) {
            return $this->json([
                "error" => "Category not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($category, Response::HTTP_OK, [], ["groups" => "categories"]);
    }

    /**
     * Create new data in Category entity
     * 
     * @Security("is_granted('ROLE_ADMIN') and user === category.getUser()") 
     * @Route("/api/categories", name="app_api_category_create", methods={"POST"})
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return void
     */
    public function create(Request $request, CategoryRepository $categoryRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $content = $request->getContent();

        try{
            $categories = $serializer->deserialize($content, Category::class, 'json');

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
     * Edit data in Category entity
     * 
     * @Security("is_granted('ROLE_ADMIN') and user === category.getUser()")
     * @Route("/api/{id}/categories", name="app_api_category_update", methods={"PUT"})
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return void
     */
    public function update(Request $request, CategoryRepository $categoryRepository, SerializerInterface $serializer, ValidatorInterface $validator, Category $category)
    {

        if (!$category) {
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
        
        $category->setName($updatedCategory->getName());
        $category->setImage($updatedCategory->getImage());

        $categoryRepository->add($category, true);

        return $this->json(["message" => "Category updated successfully"], Response::HTTP_OK);
    }

    /**
     * Delete data from Category entity
     * 
     * @Security("is_granted('ROLE_ADMIN') and user === category.getUser()")
     * @Route("/api/{id}/categories", name="app_api_category_delete", methods={"DELETE"})
     * @param CategoryRepository $categoryRepository
     * @return void
     */
    public function delete(CategoryRepository $categoryRepository, Category $category)
    {

        if (!$category) {
            return $this->json(["error" => "Category not found"], Response::HTTP_NOT_FOUND);
        }

        $categoryRepository->remove($category, true);
        return $this->json(["message" => "Category deleted successfully"], Response::HTTP_OK);
    }

}
