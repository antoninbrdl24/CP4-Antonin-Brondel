<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/meal', name: 'meal_')]
class MealController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(
        MealRepository $mealRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        // pagination de la gallerie de plats
        $pagination = $paginator->paginate(
            $mealRepository->QueryFindAllMeal(),
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('meal/index.html.twig', [
            'meals' => $pagination,
        ]);
    }


    #[Route('/show/{id}', name: 'show')]
    public function showMenu(
        Meal $meal,
        MealRepository $mealRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $meal = $mealRepository->findOneById($meal);

        return $this->render('meal/show.html.twig', [
            'meal' => $meal,
        ]);
    }
}
