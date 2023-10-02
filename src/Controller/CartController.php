<?php

namespace App\Controller;

use App\Entity\Oeuvres;
use App\Repository\OeuvresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, OeuvresRepository $productsRepository)
    {
        $panier = $session->get('panier', []);

        // On initialise des variables
        $data = [];
        $total = 0;

        foreach($panier as $id => $quantity){
            $product = $productsRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrix() * $quantity;
        }
        
        return $this->render('cart/index.html.twig', compact('data', 'total'));
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Oeuvres $oeuvre, SessionInterface $session): Response
    {
        //On récupère l'id de l'oeuvre
        $id = $oeuvre->getId();

        //On récupère le panier existant
        $panier= $session->get('panier', []);

        //On ajoute le produit dans le panier s'il n'y est pas encore
        //Sinon on incrémente sa quantité
        if(empty($panier[$id])){
            $panier[$id] = 1;
            
        } else {
            $panier[$id]++;
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    
}