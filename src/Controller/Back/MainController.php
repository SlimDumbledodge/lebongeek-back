<?php

namespace App\Controller\Back;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/back/home", name="app_back_home")
     */
    public function home(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $productRepository->findBySearch($request->query->get('query'));

        if (empty($data)) {
            //TODO faire une message en front
            return $this->json(["message" => "Aucun élément trouvé"], Response::HTTP_NO_CONTENT);
        }

        $paginateData = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/main/home.html.twig', [
            'dataLength' => count($data),
            'datas' => $paginateData->getItems(),
            'pagination' =>  $paginateData
        ]);
    }
}
