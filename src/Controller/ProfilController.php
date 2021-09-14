<?php

namespace App\Controller;

use DateTime;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil_index")
     * 
     */
    public function index(): Response
    {
        /* Pour avoir les informations de l'utilisateur connecté :
            dans twig           : app.user
            dans le contrôleur  : $this->getUser() */
            // $abonneConnecte = $this->getUser();
        return $this->render('profil/index.html.twig');
    }

    /**
     * @Route("/profil/emprunter/{id}", name="profil_emprunter")
     */
    public function emprunter(EntityManagerInterface $em, LivreRepository $lr, Livre $livre)
    {
        $livresEmpruntes = $lr->livresEmpruntes();
        if( in_array($livre, $livresEmpruntes) ) {
            $this->addFlash("danger", "Le livre <strong>" . $livre->getTitre() . "</strong> n'est pas disponible !");
            return $this->redirectToRoute("accueil");
        }

        /* EXO : l'utilisateur emprunte aujourd'hui le livre sur lequel il a cliqué */
        $emprunt = new Emprunt;
        $emprunt->setDateEmprunt(new DateTime()); // new DateTime() créé un objet DateTime avec la date du jour
        $emprunt->setLivre($livre);               // $livre a été récupéré de la bdd avec l'id qui est passé dans le chemin
        $emprunt->setAbonne( $this->getUser() );  // $this->getUser() retourne un objet Abonne contenant les infos de l'abonné actuellement connecté

        $em->persist($emprunt);     // comme $emprunt est un nouvel emprunt à insérer dans la bdd, il faut utiliser $em->persist
        $em->flush();               // $em->flush() enregistrer en bdd
        return $this->redirectToRoute("profil_index");
    }

}
