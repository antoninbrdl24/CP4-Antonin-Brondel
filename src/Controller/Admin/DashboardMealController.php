<?php

namespace App\Controller\Admin;

use App\Entity\Meal;
use App\Form\MealType;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/dashboard', name: 'admin_meal_')]
class DashboardMealController extends AbstractController
{
    #[Route('/meal', name: 'index')]
    public function index(
        MealRepository $mealRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $form = $this->createFormBuilder(null, [
            'method' => 'get',
        ])
            ->add('search', SearchType::class, [
                'label' => 'Nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->get('search')->getData();
            $query = $mealRepository->findLikeName($search);
        } else {
            $query = $mealRepository->queryFindAllMeal();
        }
        // pagination de la gallerie d'art
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );

        return $this->render('admin/meal/index.html.twig', [
            'meals' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/editMeal/{id}', name: 'edit')]
    public function editMeal(Request $request, Meal $meal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MealType::class, $meal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('pictureFile')->getData();
            if ($pictureFile) {
                $meal->setPictureFile($pictureFile);
            }
            $entityManager->flush();

            $this->addFlash('success', 'The meal has been edited successfully');

            return $this->redirectToRoute('admin_meal_index');
        }

        return $this->render('admin/meal/edit.html.twig', [
            'form' => $form->createView(),
            'meal' => $meal,
        ]);
    }

    #[Route('/deleteMeal/{id}', name: 'delete', methods: ['POST'])]
    public function deleteMeal(
        Request $request,
        Meal $meal,
        EntityManagerInterface $entityManager
    ): Response {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $meal->getId(), $submittedToken)) {
            $menu = $meal->getMenu();
            $menu->getMeals();

            if ($menu) {
                $menu->removeMeal($meal);
            }

            $entityManager->remove($meal);
            $entityManager->flush();

            $this->addFlash('danger', 'This meal has been deleted successfully');
        }

        return $this->redirectToRoute('admin_meal_index', [], Response::HTTP_SEE_OTHER);
    }
}
