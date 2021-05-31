<?php

namespace App\Controller;

use App\Repository\MembreRepository;
use App\Repository\OeuvreRepository;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(OeuvreRepository $oeuvreRepository, EvenementRepository $evenementRepository, MembreRepository $membreRepository): Response
    {
        $oeuvres = $oeuvreRepository->home();
        $evenements = $evenementRepository->home();
        $membres = $membreRepository->home();
        return $this->render('home/index.html.twig',  [
            'oeuvres' => $oeuvres,
            'evenements' => $evenements,
            'membres' => $membres
        ]);
    }
}
