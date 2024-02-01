<?php

namespace App\Controller;

use App\Entity\Dessert;
use App\Repository\DessertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dessert', name: 'dessert_')]
class DessertController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(
        DessertRepository $dessertRepository,
        Request $request,
        PaginatorInterface $paginator,
    ): Response {
        // pagination de la gallerie de plats
        $pagination = $paginator->paginate(
            $dessertRepository->QueryFindAllDessert(),
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        return $this->render('dessert/index.html.twig', [
            'desserts' => $pagination,
        ]);
    }


    #[Route('/show/{id}', name: 'show')]
    public function showMenu(
        Dessert $dessert,
        DessertRepository $dessertRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $dessert = $dessertRepository->findOneById($dessert);

        return $this->render('dessert/show.html.twig', [
            'dessert' => $dessert,
        ]);
    }
}
