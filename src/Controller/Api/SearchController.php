<?php

namespace App\Controller\Api;

use stdClass;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
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
     * @Route("/api/search", name="app_api_search", methods={"GET", "POST"})
     */
    public function searchData(Request $request, ProductRepository $productRepository, UserRepository $userRepository, SerializerInterface $serializer, PaginatorInterface $paginator): JsonResponse
    {
        // je récupère les données envoyées par la method findBySearch
        $datas = $productRepository->findBySearch($request->query->get('query'));
        // je compte les données reçus
        $countedData = count($datas);
        // si je n'ai rien reçu
        if ($countedData < 1) {
            // dans ce cas, je refais une requête vers le username, car l'utilisateur veux peut-être voir le profil d'un utilisateur qui n'a pas forcément d'annonce
            $datas = $userRepository->findBySearch($request->query->get('query'));
            // je recompte les données reçus
            $countedData = count($datas);
            // je pagine les données
            $paginateData = $paginator->paginate(
                $datas,
                $request->query->getInt('page', 1),
                4
            );
            // si j'ai reçu une donnée, alors la variable $response sera set
            $reponse = Response::HTTP_FOUND;
        };
        // si je n'ai toujours pas de donnée
        if ($countedData < 1) {
            // je retourne d'une réponse 204 (pas de contenu)
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }
        // je pagine les données
        $paginateData = $paginator->paginate(
            $datas,
            $request->query->getInt('page', 1),
            4
        );
        // je serialize la pagination avec les données
        $jsonData = $serializer->serialize($paginateData, 'json', ["groups" => "searchData"]);
        // je retourne les données, le nombre de données reçu et je change le code http en fonction du type de donnée reçu
        return $this->json(["message" => "$countedData", "data" => json_decode($jsonData)], $reponse ?? Response::HTTP_OK);
    }
}
