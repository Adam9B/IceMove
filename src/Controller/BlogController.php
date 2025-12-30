<?php

// src/Controller/BlogController.php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType; // Ensure this class exists in the src/Form directory
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_index')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $articles = $em->getRepository(Article::class)->findBy([], ['date' => 'DESC']);

        $article = new Article();

        // Seuls les admins peuvent accéder au formulaire
        $form = null;
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $article->setDate(new \DateTime());
                $em->persist($article);
                $em->flush();

                $this->addFlash('success', 'Article créé avec succès.');
                return $this->redirectToRoute('blog_index');
            }
        }

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'form' => $form?->createView(),
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(Article $article): Response
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }
}