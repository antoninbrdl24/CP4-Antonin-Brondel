<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }


    #[Route('/aboutUs', name: 'about_us')]
    public function show(): Response
    {
        return $this->render('home/aboutUs.html.twig');
    }

    public function flashMessageSuccessConnection(SessionInterface $session): Response
    {
        $successMessage = $session->get('successConnection');
        return $this->render('base.html.twig', [
            'successMessage' => $successMessage,]);
    }

    #[Route('/mentions', name: 'mentions')]
    public function showMentions(): Response
    {
        return $this->render('home/mentions.html.twig');
    }

    #[Route('/menu', name: 'menu_show')]
    public function showMenus(): Response
    {
        return $this->render('home/menus.html.twig');
    }
}
