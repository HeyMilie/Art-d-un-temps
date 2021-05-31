<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use App\Repository\OeuvreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/', name: 'panier')]
#[IsGranted("ROLE_USER")]
class PanierController extends AbstractController
{
    #[Route('/', name: 'panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository, SessionInterface $session, OeuvreRepository $oeuvreRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierAvecDonnees = [];
        foreach($panier as $id => $quantite){
            $panierAvecDonnees[]=[
                'oeuvre' => $oeuvreRepository->find($id),
                'quantite' => $quantite
            ];
        }

        $total = 0;

        foreach($panierAvecDonnees as $item){
            $totalItem = $item['product']->getPrice() * $item['quantite'];
            $total += $totalItem;
        }

 //       dd($panierAvecDonnees);

        return $this->render('panier/index.html.twig', [
            'items' => $panierAvecDonnees,
            'total' => $total
        ]);
    }

    #[Route('/new', name: 'panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/panier/add/{id}', name: 'ajout_panier')]
    public function add($id, SessionInterface $session)
    {
        $session = $request->getSession();

        $panier = $session->get('panier', []);

        if(!empty( $panier[$id] )){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("panier_index");

        /* php bin/console debug:autowiring session => recherche tous les services en rapport avec la session */
    }

    
    #[Route('/panier/remove/{id}', name: 'suppression_panier')]
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("panier_index");

    }
    /* 
    #[Route('/{id}', name: 'panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_index');
    } */
}
