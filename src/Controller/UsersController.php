<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\OrdersRepository;
use App\Repository\OrdersDetailsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte', name: 'profil_')]

class UsersController extends AbstractController
{
    #[Route('/', name: 'user')]
    #[IsGranted("ROLE_USER")]
    public function profilUser(OrdersRepository $order, OrdersDetailsRepository $details): Response
    {   
        //on oblige la connexion
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('users/profil_user.html.twig', compact('order', 'details')); 
    }
}