<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OeuvreController extends AbstractController
{
    #[Route('/oeuvre', name: 'oeuvre')]
    public function index(): Response
    {
        return $this->render('oeuvre/index.html.twig', [
            'controller_name' => 'OeuvreController',
        ]);
    }
}
