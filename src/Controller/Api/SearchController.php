<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/api/search", name="app_api_search", methods={"POST"})
     */
    public function searchData(Request $request, ProductRepository $productRepository, SerializerInterface $serializer, PaginatorInterface $paginator): JsonResponse
    {
        $data = $productRepository->findBySearch($request->query->get('query'));

        if (empty($data)) {
            //TODO faire une message en front
            return $this->json(["message" => "Aucun élément trouvé"], Response::HTTP_NO_CONTENT);
        }

        $paginateData = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        $jsonData = $serializer->serialize($paginateData, 'json', ["groups" => "searchData"]);

        $data = count($data);

        return $this->json(["message" => "$data élément(s) trouvé(s)", "data" => $jsonData], Response::HTTP_OK);
    }
}
