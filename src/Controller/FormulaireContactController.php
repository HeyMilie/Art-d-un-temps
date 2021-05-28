<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class FormulaireContactController extends AbstractController
{
    #[Route('/formulaire/contact', name: 'formulaire_contact')]
    public function index(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            
            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('artduntemps@gmail.com')
                ->subject('Vous avez reçu un email')
                ->text('Cette personne demande a soumis une demande de contact à partir du site web: '.$contactFormData['email'].\PHP_EOL.
                    $contactFormData['message'],
                    'text/plain');
            $mailer->send($message);
            $this->addFlash('success', 'Vore message a été envoyé');

            return $this->redirectToRoute('accueil');
        }
        
        return $this->render('formulaire_contact/index.html.twig', ['formulaire_contact' => $form->createView()
        ]);
    }
}
