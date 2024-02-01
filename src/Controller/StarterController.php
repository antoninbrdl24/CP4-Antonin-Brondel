<?php

namespace App\Controller;

use App\Entity\Starter;
use App\Repository\StarterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/starter', name: 'starter_')]
class StarterController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(
        StarterRepository $starterRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
          // pagination de la gallerie d'entrées
        $pagination = $paginator->paginate(
            $starterRepository->QueryFindAllStarter(),
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('starter/index.html.twig', [
            'starters' => $pagination,
        ]);
    }


    #[Route('/show/{id}', name: 'show')]
    public function showMenu(
        Starter $starter,
        StarterRepository $starterRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $starter = $starterRepository->findOneById($starter);

        return $this->render('starter/show.html.twig', [
            'starter' => $starter,
        ]);
    }
}
