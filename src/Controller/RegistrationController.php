<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\LoginAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManager;
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
    public function register(Request $request, UserPasswordEncoderInterface $passwordencoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $email, JWTService $jwt): Response
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

            //on génère le JWT de l'utilisateur
            // On crée le Header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            //On crée le Payload
            $payload = [
                'user_id' => $user->getId()
            ];

            //On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            //dd($token);

            //On envoie un mail
            $email->send(
                'no-reply@artduntemps.com',
                $user->getEmail(),
                'Activation de votre compte sur le site artduntemps',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
                );


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

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret')))
        {
            //On récupère le Payload
            $payload = $jwt->getPayload($token);

            //On récupère le user du token
            $user = $usersRepository->find($payload['user_id']);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->getIsVerified()){
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('accueil');
            }
        }
        // Ici un problème se pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');

        }

        if($user->getIsVerified()){
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('accueil');
        }

        //on génère le JWT de l'utilisateur
            // On crée le Header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            //On crée le Payload
            $payload = [
                'user_id' => $user->getId()
            ];

            //On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            //dd($token);

            //On envoie un mail
            $mail->send(
                'no-reply@artduntemps.com',
                $user->getEmail(),
                'Activation de votre compte sur le site artduntemps',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
                );
            
                $this->addFlash('success', 'Email de vérification envoyé');
                return $this->redirectToRoute('accueil');

    }
}
