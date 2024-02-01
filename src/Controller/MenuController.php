<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Menu;
use App\Entity\Comment;
use App\Entity\Dessert;
use App\Entity\Starter;
use App\Form\CommentType;
use App\Repository\MealRepository;
use App\Repository\MenuRepository;
use App\Repository\CommentRepository;
use App\Repository\DessertRepository;
use App\Repository\StarterRepository;
use App\Service\CarousselMenuManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/menu', name: 'menu_')]
class MenuController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(
        MenuRepository $menuRepository,
        StarterRepository $starterRepository,
        MealRepository $mealRepository,
        DessertRepository $dessertRepository,
        CarousselMenuManager $carousselMenuManager,
        Request $request,
    ): Response {
        $menus = $menuRepository->findAll();

        $starters = $carousselMenuManager->getRandomStarter($starterRepository);
        $meals = $carousselMenuManager->getRandomMeal($mealRepository);
        $desserts = $carousselMenuManager->getRandomDessert($dessertRepository);
        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'starters' => $starters,
            'meals' => $meals,
            'desserts' => $desserts,
        ]);
    }


    #[Route('/show/{id}', name: 'show')]
    public function showMenu(
        Menu $menu,
        EntityManagerInterface $entityManager,
        CommentRepository $commentRepository,
        Request $request
    ): Response {
        $comments = $commentRepository->findBy(['menu' => $menu], ['createdAt' => 'ASC']);
        $comment = new Comment();
        $comment->setMenu($menu);
        $comment->setAuthor($this->getUser());

        $comment->setCreatedAt();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez le commentaire dans la base de données
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirigez l'utilisateur après avoir soumis le commentaire
            return $this->redirectToRoute('menu_show', ['id' => $menu->getId()]);
        }
        return $this->render('menu/show.html.twig', [
            'menu' => $menu,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
