<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('/event', name: 'event')]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }
}
