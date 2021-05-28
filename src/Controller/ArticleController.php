<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\ArtisteArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{

    //ACCES ADMIN

    #[Route('/admin/articles', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/admin/article/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_articles");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $article->setPhoto($nouveauNom);

            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();


            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/article/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_articles");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $article->setPhoto($nouveauNom);

            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    //ACCES ARTISTES

    #[Route('/artiste/articles', name: 'artiste_articles', methods: ['GET'])]
    public function indexArtiste(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/artiste_articles.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/artiste/article/{id}/edit', name: 'artiste_article_edit', methods: ['GET', 'POST'])]
    public function editArtiste(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_articles");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $article->setPhoto($nouveauNom);

            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('artiste_articles');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/artiste/article/new', name: 'artiste_article_new', methods: ['GET', 'POST'])]
    public function newArtisteArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArtisteArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_images_articles");
            if($photoTelechargee = $form->get("photo")->getData()){
                $photo = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $photo);
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                $photoTelechargee->move($destination, $nouveauNom);
                $article->setPhoto($nouveauNom);

            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();


            return $this->redirectToRoute('artiste_articles');
        }

        return $this->render('article/artiste_article_new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }



    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
