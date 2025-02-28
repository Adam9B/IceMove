<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Utilisateur;

final class AccueilController extends AbstractController
{




    #[Route('/', name: 'app_accueil')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        return $this->render('accueil/index.html.twig', [
            'Nom' => 'exercice',
            'LeGoatDesEcheccs' => 'AdamLeBoss', ]
        );
        $utilisateur = new utilisateur();
        $utilisateur->setEmail('adam@gmail.com')->setPassword($hasher->hashPassword($utilisateur, '123'))
        ->setRoles([]);
        $em->persist($utilisateur);
        $em->flush();
        

    }



    #[Route('/exercice', name: 'pageExo')]
    public function pageExo(): Response

    {
        return $this->render('accueil/pageExo.html.twig', [
            
        ]);
        // return $this->redirectToRoute('pageExo');
        
    }



    #[Route('/programme', name: 'pageProgramme')]
    public function pageProgramme(): Response
    {
        return $this->render('accueil/pageProgramme.html.twig', [
            
        ]);
        
    }

    // #[Route('/exercice', name: 'pageExo')]
    // public function pageExo(): Response
    // {
    //     return $this->render('accueil/pageExo.html.twig', [
            
    //     ]);
    //     // return $this->redirectToRoute('pageExo');
    // }
}
