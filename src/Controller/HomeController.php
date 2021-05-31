<?php

namespace App\Controller;

use App\Repository\OeuvreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(OeuvreRepository $oeuvreRepository): Response
    {
        $oeuvres = $oeuvreRepository->home();
        return $this->render('home/index.html.twig',  [
            'oeuvres' => $oeuvres
        ]);
    }
}
