<?php

namespace App\Controller;

use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{
    /**
     * @Route("/client/voitures", name="voitures")
     */
    public function index(VoitureRepository $repository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $voitures = $paginatorInterface->paginate(
            $repository->findAllWithPagination(),
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
            "voitures" => $voitures
        ]);

    }
}
