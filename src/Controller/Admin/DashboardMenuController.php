<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/dashboard', name: 'admin_menu_')]
class DashboardMenuController extends AbstractController
{
    #[Route('/menu', name: 'index')]
    public function index(
        Menu $menu,
        MenuRepository $menuRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {


        $pagination = $paginator->paginate(
            $menuRepository->QueryFindAllMenu(),
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        return $this->render('admin/menu/index.html.twig', [
            'menus' => $pagination,
        ]);
    }


    #[Route('/editMenu/{id}', name: 'edit')]
    public function editMenu(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'The menu has been edited successfully');

            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render('admin/menu/edit.html.twig', [
            'form' => $form->createView(),
            'menu' => $menu,
        ]);
    }

    #[Route('/deleteMenu/{id}', name: 'delete')]
    public function deleteMenu(
        Request $request,
        int $id,
        Menu $menu,
        EntityManagerInterface $entityManager
    ): Response {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $menu->getId(), $submittedToken)) {
            $menu = $entityManager->find(Menu::class, $id);
            $entityManager->remove($menu);
            $entityManager->flush();

            $this->addFlash('danger', 'This menu has been deleted successfully');
        }

        return $this->redirectToRoute('admin_menu_index', [], Response::HTTP_SEE_OTHER);
    }
}
