<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Form\OeuvreType;
use App\Repository\OeuvreRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OeuvreController extends AbstractController
{
    //ACCES MEMBRES
    
    #[Route('/oeuvres', name: 'oeuvres', methods: ['GET'])]
    #[IsGranted("ROLE_MEMBRE")]
    public function homeOeuvres(OeuvreRepository $oeuvreRepository): Response
    {
        $peintures = $oeuvreRepository->findByCategorie('peinture');
        $sculptures = $oeuvreRepository->findByCategorie('sculpture');
        $ceramiques = $oeuvreRepository->findByCategorie('céramique');
        //dd($sculptures); 
        return $this->render('oeuvre/oeuvres.html.twig', [
            'peintures' => $peintures,
            'sculptures' => $sculptures,
            'ceramiques' => $ceramiques
        ]);
    }

    #[Route('/oeuvres/oeuvre/{id}', name: 'oeuvre', methods: ['GET'])] 
    public function ficheOeuvre(Oeuvre $oeuvre): Response
    {
        
        return $this->render('oeuvre/oeuvre.html.twig', [
            'oeuvre' => $oeuvre
        ,]);
    }

    // ACCES ADMIN

    #[Route('admin/oeuvres/', name: 'bo_oeuvres', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function index(OeuvreRepository $oeuvreRepository): Response
    {
        return $this->render('oeuvre/index.html.twig', [
            'oeuvres' => $oeuvreRepository->findAll(),
        ]);
    }



    #[Route('admin/oeuvre/new', name: 'admin_oeuvre_new', methods: ['GET', 'POST'])]
    public function newOeuvreAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_oeuvres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $oeuvre->setPhoto($nouveauNom);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('bo_oeuvres');
        }

        
        return $this->render('oeuvre/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/oeuvre/{id}/edit', name: 'oeuvre_edit', methods: ['GET', 'POST'])]
    public function editOeuvreAdmin(Request $request, Oeuvre $oeuvre): Response
    {
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_oeuvres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $oeuvre->setPhoto($nouveauNom);

            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bo_oeuvres');
            
        }

        return $this->render('oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/oeuvre/{id}', name: 'oeuvre_show', methods: ['GET'])]
    public function showOeuvreAdmin(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    // ACCES ARTISTE

    #[Route('artiste/oeuvres/', name: 'artiste_oeuvres', methods: ['GET'])]
    public function artisteOeuvres(OeuvreRepository $oeuvreRepository): Response
    {
        return $this->render('oeuvre/artiste_oeuvres.html.twig', [
            'oeuvres' => $oeuvreRepository->findAll(),
        ]);
    }

    #[Route('artiste/oeuvre/new', name: 'artiste_oeuvre_new', methods: ['GET', 'POST'])]
    public function newOeuvreArtiste(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_oeuvres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $oeuvre->setPhoto($nouveauNom);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('profil_artiste');
        }

        return $this->render('oeuvre/artiste_oeuvre_new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('artiste/oeuvre/{id}', name: 'artiste_oeuvre_show', methods: ['GET'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/artiste_oeuvre_show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    #[Route('artiste/oeuvre/{id}/edit', name: 'artiste_oeuvre_edit', methods: ['GET', 'POST'])]
    public function editOeuvreArtiste(Request $request, Oeuvre $oeuvre): Response
    {
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_oeuvres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $oeuvre->setPhoto($nouveauNom);

            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_artiste');
            
        }

        return $this->render('oeuvre/artiste_oeuvre_edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }




    #[Route('artiste/oeuvre/{id}', name: 'oeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, Oeuvre $oeuvre): Response
    {
        if ($this->isCsrfTokenValid('delete' . $oeuvre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($oeuvre);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('bo_oeuvres');
    }
}
