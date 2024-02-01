<?php

namespace App\Controller\Admin;

use App\Entity\Starter;
use App\Form\StarterType;
use App\Repository\StarterRepository;
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

#[Route('/admin/dashboard', name: 'admin_starter_')]
class DashboardStarterController extends AbstractController
{
    #[Route('/starter', name: 'index')]
    public function index(
        StarterRepository $starterRepository,
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
            $query = $starterRepository->findLikeName($search);
        } else {
            $query = $starterRepository->queryFindAllStarter();
        }
        // pagination de la gallerie d'art
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );

        return $this->render('admin/starter/index.html.twig', [
            'starters' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/editStarter/{id}', name: 'edit')]
    public function editStarter(Request $request, Starter $starter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StarterType::class, $starter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('pictureFile')->getData();
            if ($pictureFile) {
                $starter->setPictureFile($pictureFile);
            }
            $entityManager->flush();

            $this->addFlash('success', 'The starter has been edited successfully');

            return $this->redirectToRoute('admin_starter_index');
        }

        return $this->render('admin/starter/edit.html.twig', [
            'form' => $form->createView(),
            'starter' => $starter,
        ]);
    }

    #[Route('/deleteStarter/{id}', name: 'delete', methods: ['POST'])]
    public function deleteStarter(
        Request $request,
        Starter $starter,
        EntityManagerInterface $entityManager
    ): Response {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $starter->getId(), $submittedToken)) {
            $menu = $starter->getMenu();
            $menu->getStarters();

            if ($menu) {
                $menu->removeStarter($starter);
            }

            $entityManager->remove($starter);
            $entityManager->flush();

            $this->addFlash('danger', 'This starter has been deleted successfully');
        }

        return $this->redirectToRoute('admin_starter_index', [], Response::HTTP_SEE_OTHER);
    }
}
