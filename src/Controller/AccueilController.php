<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(LivreRepository $lr): Response
    {
        return $this->render('accueil/index.html.twig', [ 
            "livres" => $lr->findAll(),
            "livres_empruntes" => $lr->livresEmpruntes()
        ]);
    }
}
