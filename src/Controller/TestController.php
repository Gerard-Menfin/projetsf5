<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     * 
     * C'est la 1ere route de notre projet
     */
    #[Route('/test', name: 'test')]  #version PHP 8
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new aergqdrgqsrgt!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    /**
     * @Route("/test/nouvelle-route", name="test_nouvelle")
     * 
     * ⚠ Il ne peut pas y avoir 2 routes avec le même 'name'
     * 
     */
    public function nouvelleRoute(): Response
    {
        /* La méthode 'render' permet de générer l'affichage d'un fichier vue qui se trouve dans le dossier 'templates'
            Le 1er paramètre est le nom du fichier
            Le 2ème paramètre n'est pas obligatoire. Il doit être de type array et contiendra toutes les variables que l'on veut
                    transmettre à la vue
        */
        return $this->render("base.html.twig", [ "prenom" => "Didier" ]);
    }

    /**
     * @Route("/test/tableau", name="test_tableau")
     */
    public function tableau()
    {
        $tableau = [ "un", 2, true ];
        $tableau2 = [ "nom" => "Cérien", "prenom" => "Jean", "age" => 30 ];

        // EXO : Je veux transmettre la valeur de la variable $tableau2 à ma vue dans une variable nommée "personne"
        // Ensuite afficher, "Je m'appelle " suivi du prénom, nom et age 

        return $this->render("test/tableau.html.twig", [ "tableau" => $tableau, "personne" => $tableau2 ]);
        echo "ceci ne sera jamais affiché";
    }

    /**
     * @Route("/test/objet")
     */
    public function objet()
    {
        $objet = new \stdClass();
        $objet->nom = "Mentor";
        $objet->prenom = "Gérard";
        $objet->age = "54";

        return $this->render("test/tableau.html.twig", [ "personne" => $objet ]);
    }

    /**
     * @Route("/test/salut/{prenom}")
     * 
     * Dans le chemin, les {} signifient que cette partie du chemin est variable.
     * ça peut être n'importe quel chaîne de caractères. Le nom mis entre {} est le 
     * nom de la variable passé en paramètre
     */
    public function prenom($prenom)
    {
        return $this->render("base.html.twig", [ "prenom" => $prenom ]);
    }

    /*
        EXO : vous allez ajouter une route, "/test/liste/{nombre}"
                Le nombre passé en paramètre devra être envoyé à une vue qui 
                étend base.html.twig. 
                Cette vue va afficher la liste des nombres de 1 jusqu'au nombre passé 
                dans le chemin dans une table HTML
                Dans la première colonne, le nombre 
                Dans la deuxième colonne, le nombre multiplié par 2
*/

    /**
     * @Route("/test/liste/{nombre?}")
     */
    public function nombre($nombre)
    {
        return $this->render("test/liste.html.twig", [ "nombre" => $nombre ]);
    }

/*
        EXO 2 : Créer une nouvelle route qui prend un nombre dans l'URL et qui affiche
        le résultat de ce nombre au carré
    */
}


