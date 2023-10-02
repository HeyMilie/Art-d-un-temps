<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Events;
use App\Entity\Oeuvres;
use App\Form\NewUserType;
use App\Form\EditUserType;
use App\Form\NewEventType;
use App\Form\EditEventType;
use App\Form\NewOeuvreType;
use App\Form\EditOeuvreType;
use Doctrine\ORM\EntityManager;
use App\Repository\UsersRepository;
use App\Repository\EventsRepository;
use App\Security\LoginAuthenticator;
use App\Repository\OeuvresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/admin', name: 'admin_')]

class AdminController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    // Liste des utilisateurs du site
    #[Route('/utilisateurs', name: 'utilisateurs')]
    public function usersList(UsersRepository $users){
        return $this->render("admin/users.html.twig", [
            'users' => $users->findAll()
        ]);

    }
    // Modifier un utilisateur du site
    #[Route('/utilisateur/modifier/{id}', name: 'modifier_utilisateur')]
    public function editUser(Users $user, Request $request)
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $destination = $this->getParameter("dossier_images_membres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $user->setPhoto($nouveauNom);

            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/edituser.html.twig', [
            'user' => $user,
            'userForm' => $form->createView()
        ]);
    }
    //Ajouter un nouveau membre
    #[Route('/utilisateur/ajouter', name: 'ajouter_utilisateur')]
    public function newUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(NewUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // encode the plain password
             $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $destination = $this->getParameter("dossier_images_membres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $user->setPhoto($nouveauNom);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/newuser.html.twig', [
            'user' => $user,
            'newUserForm' => $form->createView(),
        ]);
    }

    //Afficher les informations d'un utilisateur
    #[Route('/utilisateur/profil/{id}', name: 'voir_utilisateur')]
    public function showUser(Users $user, OeuvresRepository $oeuvre): Response
    {
        return $this->render('admin/showuser.html.twig', [
            'user' => $user,
            'oeuvre' => $oeuvre->findAll(),
        ]);
    }

    //Supprimer un utilisateur
    #[Route('/utilisateur/supprimer/{id}', name: 'supprimer_utilisateur')]
    public function deleteUser(Request $request, Users $user): Response
    {
        if($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Suppression réalisée avec succès');
        return $this->redirectToRoute('admin_utilisateurs');
    }

    //Liste des oeuvres
    #[Route('/oeuvres', name: 'oeuvres')]
    public function oeuvresList(OeuvresRepository $oeuvres)
    {
        return $this->render("admin/oeuvres.html.twig", [
            'oeuvres' => $oeuvres->findAll()
        ]);
    }

    //Ajouter une oeuvre
    #[Route('/oeuvre/ajouter', name: 'ajouter_oeuvre')]
    public function newOeuvre(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvre = new Oeuvres();
        $form = $this->createForm(NewOeuvreType::class, $oeuvre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_oeuvres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $oeuvre->setPhoto($nouveauNom);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('admin_oeuvres');

        }

        return $this->render('admin/newoeuvre.html.twig', [
            'oeuvre' => $oeuvre,
            'newOeuvreForm' => $form->createView(),
        ]);

    }

    //Afficher les informations d'une oeuvre
    #[Route('/oeuvres/{id}', name: 'voir_oeuvre')]
    public function showOeuvre(Oeuvres $oeuvre): Response
    {
        return $this->render('admin/showoeuvre.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    //Modifier une oeuvre
    #[Route('/oeuvre/modifier/{id}', name: 'modifier_oeuvre')]
    public function editOeuvre(Oeuvres $oeuvre, Request $request)
    {
        $form = $this->createForm(EditOeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_oeuvres");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $oeuvre->setPhoto($nouveauNom);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oeuvre);
            $entityManager->flush();

            $this->addFlash('message', 'Oeuvre modifiée avec succès');
            return $this->redirectToRoute('admin_oeuvres');

        }

        return $this->render('admin/editoeuvre.html.twig', [
            'oeuvre' => $oeuvre,
            'oeuvreForm' => $form->createView(),
        ]);
    }

    //Supprimer une oeuvre
    #[Route('/oeuvre/supprimer/{id}', name: 'supprimer_oeuvre')]
    public function deleteOeuvre(Request $request, Oeuvres $oeuvre): Response
    {
        if($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($oeuvre);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Suppression réalisée avec succès');
        return $this->redirectToRoute('admin_oeuvre');
    }

    //Liste  des événements
    #[Route('/evenements', name: 'evenements')]
    public function eventsList(EventsRepository $events, UsersRepository $user)
    {
        return $this->render("admin/events.html.twig", [
            'events' => $events->findAll(),
            'user' => $user->findAll(),
        ]);
    }

    //Ajouter un événement
    #[Route('/evenement/ajouter', name: 'ajouter_evenement')]
    public function newEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Events();
        $form = $this->createForm(NewEventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('admin_evenements');
        }

        return $this->render('admin/newevent.html.twig', [
            'event' => $event,
            'newEventForm' => $form->createView(),
        ]);
    }

    //Modifier un événement
    #[Route('/evenement/modifier/{id}', name: 'modifier_evenement')]
    public function editEvent(Events $event, Request $request)
    {
        $form = $this->createForm(EditEventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('admin_evenements');
        }

        return $this->render('admin/editevent.html.twig', [
            'event' => $event,
            'eventForm' => $form->createView(),
        ]);
    }

    //Afficher les informations d'un événement
    #[Route('/evenement/{id}', name: 'voir_evenement')]
    public function showEvent(Events $event): Response
    {
        return $this->render('admin/showevent.html.twig', [
            'event' => $event,
        ]);
    }

    //Supprimer un événement
    #[Route('/evenement/supprimer/{id}', name: 'supprimer_evenement')]
    public function deleteEvent(Request $request, Events $event): Response
    {
        if($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Suppression réalisée avec succès');
        return $this->redirectToRoute('admin_evenements');
    }

}