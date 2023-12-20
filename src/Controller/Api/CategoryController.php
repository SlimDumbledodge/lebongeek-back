<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Service\CategoryService;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    /**
     * Get all data from Category entity
     * 
     * @Route("/api/list/categories", name="app_api_list_category", methods={"GET"})
     * @param CategoryRepository $categoriesRepository
     * @return JsonResponse
     */
    public function categoryList(CategoryRepository $categoriesRepository): JsonResponse
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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/api/categories", name="app_api_category_create", methods={"POST"})
     *
     * @param Request $request
     * @param CategoryService $categoryService
     * @return JsonResponse
     */
    public function create(Request $request, CategoryService $categoryService): JsonResponse
    {
        return $categoryService->add($request->getContent());
    }

    /**
     * Edit data in Category entity
     * 
     * @IsGranted("ROLE_ADMIN")
     * @Route("/api/{id}/categories", name="app_api_category_update", methods={"PUT"})
     *
     * @param Request $request
     * @param Category $category
     * @param CategoryService $categoryService
     * @return JsonResponse
     */
    public function update(Request $request, Category $category, CategoryService $categoryService): JsonResponse
    {
        return $categoryService->edit($request->getContent(), $category);
    }

    /**
     * Delete data from Category entity
     * 
     * @IsGranted("ROLE_ADMIN")
     * @Route("/api/{id}/categories", name="app_api_category_delete", methods={"DELETE"})
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     */
    public function delete(CategoryRepository $categoryRepository, Category $category): JsonResponse
    {

        if (!$category) {
            return $this->json(["error" => "Category not found"], Response::HTTP_NOT_FOUND);
        }

        $categoryRepository->remove($category, true);
        return $this->json(["message" => "Category deleted successfully"], Response::HTTP_OK);
    }
}
