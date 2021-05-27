<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\EditProfilArtisteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Encoder;


class ProfilController extends AbstractController
{
    // PROFIL MEMBRE

    #[Route('/profil', name: 'profil_membre')]
    #[IsGranted("ROLE_MEMBRE")]
    public function profilMembre(): Response
    { 
         return $this->render('profil/profil_membre.html.twig');

    }
    
    #[Route('/profil/{id}', name: 'edit_profil_membre', methods: ['GET', 'POST'])]
    public function editMembre(Request $request, Membre $membre, Encoder $encoder): Response
    {
        $form = $this->createForm(EditProfilMembreType::class, $membre,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( $password = $form->get("password")->getData() ){
                $password = $encoder->encodePassword($membre, $password);
                $membre->setPassword($password);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_membre');
        }

        return $this->render('profil/edit_profil_membre.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    // PROFIL ARTISTE

    #[Route('/artiste/profil', name: 'profil_artiste')]
    #[IsGranted("ROLE_ARTISTE")]
    public function profilArtiste(): Response
    { 
        return $this->render('profil/profil_artiste.html.twig');
        
    }

    #[Route('/artiste/profil/{id}', name: 'edit_profil_artiste', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ARTISTE")]
    public function editArtiste(Request $request, Membre $membre, Encoder $encoder): Response
    {
        $form = $this->createForm(EditProfilArtisteType::class, $membre,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( $password = $form->get("password")->getData() ){
                $password = $encoder->encodePassword($membre, $password);
                $membre->setPassword($password);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_artiste');
        }

        return $this->render('profil/edit_profil_artiste.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    // PROFIL ADMIN

    #[Route('/admin/profil', name: 'profil_admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function profilAdmin(): Response
    { 
        return $this->render('profil/profil_admin.html.twig');  
    }
}
