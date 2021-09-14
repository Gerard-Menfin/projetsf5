<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/abonne")
 */
class AbonneController extends AbstractController
{
    /**
     * @Route("/", name="abonne_index", methods={"GET"})
     */
    public function index(AbonneRepository $abonneRepository): Response
    {
        return $this->render('abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/nouveau", name="abonne_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            // Récupération et encodage du mot de passe tapé dans le formulaire
            $mdp = $form->get("password")->getData();  // il faut utiliser 'getData' pour avoir la 'value' de l'input password
            $mdp = $hasher->hashPassword($abonne, $mdp);
            $abonne->setPassword($mdp);

            $entityManager->persist($abonne);
            $entityManager->flush();
            $this->addFlash("success", "L'abonné a été enregistré");
            return $this->redirectToRoute('abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonne/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="abonne_show", methods={"GET"})
     */
    public function show(Abonne $abonne): Response
    {
        return $this->render('abonne/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="abonne_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPasswordHasherInterface $hasher, Abonne $abonne): Response
    {
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération et encodage du mot de passe tapé dans le formulaire
            $mdp = $form->get("password")->getData();  // il faut utiliser 'getData' pour avoir la 'value' de l'input password
            if( $mdp ){  // si le mot de passe n'est pas vide...
                $mdp = $hasher->hashPassword($abonne, $mdp);
                $abonne->setPassword($mdp);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "L'abonné a été modifié");
            return $this->redirectToRoute('abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonne/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="abonne_delete", methods={"POST"})
     */
    public function delete(Request $request, Abonne $abonne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('abonne_index', [], Response::HTTP_SEE_OTHER);
    }
}
