<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\OeuvresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function index(SessionInterface $session, OeuvresRepository $oeuvresRepository, EntityManagerInterface $em): Response
    {
        //on oblige la connexion
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);
        //dd($panier);
        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('accueil');
        }

        //Le panier n'est pas vide, on créer la commande
        $order = new Orders();
        
        //on remplit la commande
        $order->setUsers($this->getUser());
        $order->setReference(uniqid());
        
        //On parcourt le panier pour créer les détails de commande
        foreach($panier as $item => $quantity){
            $orderDetails = new OrdersDetails();
            //dd($order);
            //On va chercher le produit
            $oeuvre = $oeuvresRepository->find($item);
            //dd($oeuvre);
            $price = $oeuvre->getPrix();

            //On crée le détail de commande
            $orderDetails->setOeuvres($oeuvre);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);

        }

        //On persiste et on flush
        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        
        $this->addFlash('message', 'Votre commande a été réalisée avec succès');
        return $this->redirectToRoute('accueil');
    }
}
