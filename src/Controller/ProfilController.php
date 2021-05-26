<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil_index')]

    #[IsGranted("ROLE_")]

    public function index(): Response
    { 
         return $this->render('profil/index.html.twig');

    }

    #[Route('/admin/profil', name: 'profil-admin')]
    #[IsGranted("ROLE_ADMIN")]

    public function profilAdmin(): Response
    { 
        return $this->render('profil/profil-admin.html.twig');
        
    }
}
