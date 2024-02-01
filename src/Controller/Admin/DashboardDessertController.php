<?php

namespace App\Controller\Admin;

use App\Entity\Dessert;
use App\Form\DessertType;
use App\Repository\DessertRepository;
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

#[Route('/admin/dashboard', name: 'admin_dessert_')]
class DashboardDessertController extends AbstractController
{
    #[Route('/dessert', name: 'index')]
    public function index(
        DessertRepository $dessertRepository,
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
            $query = $dessertRepository->findLikeName($search);
        } else {
            $query = $dessertRepository->queryFindAllDessert();
        }
        // pagination de la gallerie d'art
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );

        return $this->render('admin/dessert/index.html.twig', [
            'desserts' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/editDessert/{id}', name: 'edit')]
    public function editDessert(Request $request, Dessert $dessert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DessertType::class, $dessert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('pictureFile')->getData();
            if ($pictureFile) {
                $dessert->setPictureFile($pictureFile);
            }
            $entityManager->flush();

            $this->addFlash('success', 'The dessert has been edited successfully');

            return $this->redirectToRoute('admin_dessert_index');
        }

        return $this->render('admin/dessert/edit.html.twig', [
            'form' => $form->createView(),
            'dessert' => $dessert,
        ]);
    }

    #[Route('/deleteDessert/{id}', name: 'delete', methods: ['POST'])]
    public function deleteDessert(
        Request $request,
        Dessert $dessert,
        EntityManagerInterface $entityManager
    ): Response {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $dessert->getId(), $submittedToken)) {
            $menu = $dessert->getMenu();
            $menu->getDesserts();

            if ($menu) {
                $menu->removeDessert($dessert);
            }

            $entityManager->remove($dessert);
            $entityManager->flush();

            $this->addFlash('danger', 'This dessert has been deleted successfully');
        }

        return $this->redirectToRoute('admin_dessert_index', [], Response::HTTP_SEE_OTHER);
    }
}
