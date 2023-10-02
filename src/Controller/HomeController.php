<?php

namespace App\Controller;


use App\Repository\UsersRepository;
use App\Repository\EventsRepository;
use App\Repository\OeuvresRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(OeuvresRepository $oeuvresRepository, EventsRepository $eventsRepository, UsersRepository $usersRepository): Response
    {
        $oeuvres = $oeuvresRepository->home();
        $events = $eventsRepository->home();
        $users = $usersRepository->home('ROLE_ARTISTE');
        return $this->render('home/index.html.twig',  [
            'oeuvres' => $oeuvres,
            'events' => $events,
            'users' => $users
        ]);
    }
}
