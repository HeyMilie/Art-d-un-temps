<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventsController extends AbstractController
{
    #[Route('/evenements', name: 'events')]
    public function index(EventsRepository $eventsRepository): Response
    {
        return $this->render('event/pageevenements.html.twig', [
            'events' => $eventsRepository->findAll(),
        ]);
    }
}
