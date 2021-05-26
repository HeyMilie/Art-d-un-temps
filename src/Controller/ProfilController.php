<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil_membre')]
    #[IsGranted("ROLE_MEMBRE")]
    public function profilMembre(): Response
    { 
         return $this->render('profil/profil_membre.html.twig');

    }

    #[Route('/artiste/profil', name: 'profil_artiste')]
    #[IsGranted("ROLE_ARTISTE")]
    public function profilArtiste(): Response
    { 
        return $this->render('profil/profil_artiste.html.twig');
        
    }

    #[Route('/admin/profil', name: 'profil_admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function profilAdmin(): Response
    { 
        return $this->render('profil/profil_admin.html.twig');
        
    }
}
