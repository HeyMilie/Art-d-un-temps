<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Encoder;

#[Route('admin/membre')]
class MembreController extends AbstractController
{
    #[Route('s', name: 'membre_index', methods: ['GET'])]
    public function index(MembreRepository $membreRepository): Response
    {
        return $this->render('membre/index.html.twig', [
            'membres' => $membreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'membre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Encoder $encoder): Response
    {
        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get("password")->getData();
            $password = $encoder ->encodePassword($membre, $password);
            $membre->setPassword( $password );
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('membre_index');
        }

        return $this->render('membre/new.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'membre_show', methods: ['GET'])]
    public function show(Membre $membre): Response
    {
        return $this->render('membre/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    #[Route('/{id}/edit', name: 'membre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Membre $membre, Encoder $encoder): Response
    {
        $form = $this->createForm(MembreType::class, $membre,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( $password = $form->get("password")->getData() ){
                $password = $encoder->encodePassword($membre, $password);
                $membre->setPassword($password);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membre_index');
        }

        return $this->render('membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'membre_delete', methods: ['POST'])]
    public function delete(Request $request, Membre $membre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($membre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('membre_index');
    }
}
