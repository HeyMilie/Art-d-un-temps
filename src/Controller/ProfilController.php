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

    #[IsGranted("IS_AUTHENTICATED_FULLY")]

    public function index(): Response
    { 
         return $this->render('profil/index.html.twig');

<<<<<<< HEAD
=======
        /* ]); */
>>>>>>> 99978fc93e17dc8991fa75f059f6afa326f40d10
    }
}
