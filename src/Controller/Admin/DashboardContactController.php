<?php

namespace App\Controller\Admin;

use App\Repository\ContactRepository;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/dashboard', name: 'admin_contact_')]
class DashboardContactController extends AbstractController
{
    #[Route('/contact', name: 'index')]
    public function showContact(
        ContactRepository $contactRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $contactRepository->findAll();
        // Barre de recherche
        $form = $this->createFormBuilder(null, [
            'method' => 'get',
        ])
            ->add('demandType', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Retour sur notre service' => 'retour_service',
                    'Axe d\'amélioration' => 'axe_amelioration',
                    'Problème rencontré' => 'probleme_rencontre',
                    'Autres' => 'autres',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandType = $form->get('demandType')->getData();
            $query = $contactRepository->findByDemandType($demandType);
        } else {
            $query = $contactRepository->queryFindAllContact();
        }

        // pagination des demandes de contact
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            5/*limit per page*/
        );

        return $this->render('admin/contact.html.twig', [
            'contacts' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deleteContact/{id}', name: 'delete')]
    public function deleteContact(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete' . $contact->getId(), $submittedToken)) {
            $entityManager->remove($contact);
            $entityManager->flush();

            $this->addFlash('danger', 'This contact has been deleted successfully');
        }

        return $this->redirectToRoute('admin_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
