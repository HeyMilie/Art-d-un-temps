<?php

namespace App\Controller;

use App\Entity\Oeuvres;
use App\Repository\OeuvresRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OeuvresController extends AbstractController
{
    #[Route('/oeuvres', name: 'oeuvres', methods: ['GET'])]
    public function homeOeuvres(OeuvresRepository $oeuvresRepository): Response
    {
        $peintures = $oeuvresRepository->findByCategorie('peinture');
        $sculptures = $oeuvresRepository->findByCategorie('sculpture');
        $ceramiques = $oeuvresRepository->findByCategorie('céramique');
        //dd($sculptures); 
        return $this->render('oeuvres/pageoeuvres.html.twig', [
            'peintures' => $peintures,
            'sculptures' => $sculptures,
            'ceramiques' => $ceramiques
        ]);
    }

    #[Route('/oeuvres/oeuvre/{id}', name: 'oeuvre', methods: ['GET'])] 
    public function ficheOeuvre(Oeuvres $oeuvre): Response
    {
        
        return $this->render('oeuvres/ficheoeuvre.html.twig', [
            'oeuvre' => $oeuvre
        ,]);
    }
}
