<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(VoitureRepository $repo,PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $rechercheVoiture = new RechercheVoiture();

        $form = $this->createForm(RechercheVoitureType::class,$rechercheVoiture);
        $form->handleRequest($request);

        $voitures = $paginatorInterface->paginate(
            $repo->findAllWithPagination($rechercheVoiture),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        $voitures->setCustomParameters([
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination and foundation_v6_pagination)
            'size' => 'medium', # small|large (for template: twitter_bootstrap_v4_pagination)
            'style' => 'bottom',
            'span_class' => 'whatever',
        ]);
        
        return $this->render('voiture/voitures.html.twig',[
            "voitures" => $voitures,
            "form" => $form->createView(),
            "admin" => true
        ]);
    }

    /**
     * @Route("/admin/creation", name="adminCreationVoiture")
     * @Route("/admin/{id}", name="adminModificationVoiture", methods="GET|POST")
     */
    public function adminCreationEtModificationVoiture(Voiture $voiture = null, Request $request, EntityManagerInterface $entityManager): Response 
    {
        if(!$voiture) {
            $voiture = new Voiture();
        }
        
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $modification = $voiture->getId() !== null;
            $entityManager->persist($voiture);
            $entityManager->flush();
            // $this->addFlash('success', "L'action a été effectué");
            $this->addFlash("success", ($modification) ? "La modification de la voiture " . $voiture->getImmatriculation() . " a été effectuée" : "La création de la voiture a été effectuée");
            return $this->redirectToRoute("admin");
        }

        return $this->render('admin/creationEtModificationVoiture.html.twig',[
            "voiture" => $voiture,
            "form" => $form->createView(),
            'isModification' => $voiture->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/{id}", name="adminSuppressionVoiture", methods="SUP")
     */
    public function suppression(Voiture $voiture, Request $request, EntityManagerInterface $entityManager) {
        if($this->isCsrfTokenValid("SUP".$voiture->getId(), $request->get("_token"))) {
            $entityManager->remove($voiture);
            $entityManager->flush();
            // $this->addFlash('success', "L'action a été effectué");
            $this->addFlash("success","La voiture avec la plaque d'immatriculation : " . $voiture->getImmatriculation() . " a été supprimée");
            return $this->redirectToRoute("admin");
        }
    }
}
