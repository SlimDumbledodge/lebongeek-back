<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryService
{
    private $serializer;

    private $validator;

    private $categoryRepository;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, CategoryRepository $categoryRepository)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Create new data in Category entity
     *
     * @param [type] $content
     * @return JsonResponse
     */
    public function add($content): JsonResponse
    {
        try {
            $categories = $this->serializer->deserialize($content, Category::class, 'json');
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return new JsonResponse(["message" => $err . " : JSON invalide"], Response::HTTP_BAD_REQUEST);
        }

        $errors = $this->validator->validate($categories);
        if (count($errors) > 0) {
            $dataErrors = [];
            //on boucle sur les erreurs
            foreach ($errors as $error) {
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            //on retourne les erreurs
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->categoryRepository->add($categories, true);

        return new JsonResponse(["message" => "Category created successfully"], Response::HTTP_CREATED);
    }

    /**
     * Edit data in Category entity
     *
     * @param [type] $content
     * @param [type] $category
     * @return JsonResponse
     */
    public function edit($content, $category): JsonResponse
    {
        if (!$category) {
            return new JsonResponse(["error" => "Category not found"], Response::HTTP_NOT_FOUND);
        }

        try {
            $updatedCategory = $this->serializer->deserialize($content, Category::class, 'json');
        } catch (NotEncodableValueException $err) {
            // plutôt que de faire le comportement de base de l'exception (message rouge moche), je renvoi un json
            return new JsonResponse(["message" => "JSON invalide"], Response::HTTP_BAD_REQUEST);
        }

        $errors = $this->validator->validate($updatedCategory);
        if (count($errors) > 0) {
            $dataErrors = [];
            //on boucle sur les erreurs
            foreach ($errors as $error) {
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            //on retourne les erreurs
            return new JsonResponse($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category->setName($updatedCategory->getName());
        $category->setImage($updatedCategory->getImage());

        $this->categoryRepository->add($category, true);

        return new JsonResponse(["message" => "Category updated successfully"], Response::HTTP_OK);
    }
}
