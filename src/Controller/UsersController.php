<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte', name: 'profil_')]

class UsersController extends AbstractController
{
    #[Route('/', name: 'user')]
    #[IsGranted("ROLE_USER")]
    public function profilUser(): Response
    {
        return $this->render('users/profil_user.html.twig');
    }
}