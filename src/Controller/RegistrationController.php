<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordencoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // encode the plain password
             $user->setPassword(
                $passwordencoder->encodePassword(
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

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
