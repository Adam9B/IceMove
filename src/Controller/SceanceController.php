<?php

// src/Controller/SceanceController.php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use App\Entity\Sceance; // Correction : Sceance avec un 'c'
use App\Form\SceanceType; // Correction : SceanceType avec un 'c'
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class SceanceController extends AbstractController // Correction : SceanceController avec un 'c'
{
    #[Route('/sceance/new', name: 'app_sceance_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $utilisateur = $this->getUser(); // ✅ Bonne méthode !

    if (!$utilisateur) {
        $this->addFlash('error', 'Vous devez être connecté pour créer une séance.');
        return $this->redirectToRoute('app_login');
    }

    $sceance = new Sceance();
    $sceance->setUtilisateur($utilisateur); // ✅ Bon setter

    $form = $this->createForm(SceanceType::class, $sceance);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($sceance);
        $entityManager->flush();

        $this->addFlash('success', 'Séance créée avec succès !');
        return $this->redirectToRoute('app_sceance_show', ['id' => $sceance->getId()]);
    }

    return $this->render('sceance/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/sceance/{id}', name: 'app_sceance_show')] // Correction : /sceance/{id}
    public function show(Sceance $sceance): Response // Correction : Sceance avec un 'c'
    {

        if (!$sceance) {
            throw $this->createNotFoundException('Séance non trouvée');
        }
        // Vérifier si l'utilisateur connecté est le propriétaire de la séance
        $utilisateur = $this->getUser(); // ✅ Bonne méthode !
        if ($utilisateur !== $sceance->getUtilisateur()) {
            $this->addFlash('error', 'Vous n\'avez pas la permission de voir cette séance.');
            return $this->redirectToRoute('app_sceance_index');
        }

        

        // if (!$utilisateur) {
        //     $this->addFlash('error', 'Vous devez être connecté pour créer une séance.');
        //     return $this->redirectToRoute('app_login');
        // }   


        
        
      

        return $this->render('sceance/show.html.twig', [ // Correction : templates/sceance/show.html.twig
            'sceance' => $sceance, // Correction : sceance avec un 'c'
        ]);

    }


    #[Route('/sceance/{id}/edit', name: 'app_sceance_edit')] // Correction : /sceance/{id}/edit
    public function edit(Request $request, Sceance $sceance, EntityManagerInterface $entityManager): Response // Correction : Sceance avec un 'c'
    {

        // Vérifier si l'utilisateur connecté est le propriétaire de la séance
        $utilisateur = $this->getUser(); // ✅ Bonne méthode !
        if ($utilisateur !== $sceance->getUtilisateur()) {
            $this->addFlash('error', 'Vous n\'avez pas la permission de modifier cette séance.');
            return $this->redirectToRoute('app_sceance_index');
        }
        if (!$sceance) {
            throw $this->createNotFoundException('Séance non trouvée');
        }

        $form = $this->createForm(SceanceType::class, $sceance); // Correction : SceanceType avec un 'c'

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Séance mise à jour avec succès !');
            return $this->redirectToRoute('app_sceance_show', ['id' => $sceance->getId()]);
        }

        return $this->render('sceance/edit.html.twig', [ // Correction : templates/sceance/edit.html.twig
            'form' => $form->createView(),
            'sceance' => $sceance, // Correction : sceance avec un 'c'
        ]);
    }
    #[Route('/sceance/{id}/delete', name: 'app_sceance_delete')] // Correction : /sceance/{id}/delete
    public function delete(Request $request, Sceance $sceance, EntityManagerInterface $entityManager): Response // Correction : Sceance avec un 'c'
    {
        // Vérifier si l'utilisateur connecté est le propriétaire de la séance
        $utilisateur = $this->getUser(); // ✅ Bonne méthode !
        if ($utilisateur !== $sceance->getUtilisateur()) {
            $this->addFlash('error', 'Vous n\'avez pas la permission de supprimer cette séance.');
            return $this->redirectToRoute('app_sceance_index');
        }
        if (!$sceance) {
            throw $this->createNotFoundException('Séance non trouvée');
        }

        if ($this->isCsrfTokenValid('delete' . $sceance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sceance);
            $entityManager->flush();

            $this->addFlash('success', 'Séance supprimée avec succès !');
        }

        return $this->redirectToRoute('app_sceance_index'); // Correction : app_sceance_index
    }

    #[Route('/sceance', name: 'app_sceance_index', methods: ['GET'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    ): Response {

        $utilisateur = $this->getUser(); // ✅ Bonne méthode !

    if (!$utilisateur) {
        $this->addFlash('error', 'Vous devez être connecté');
        return $this->redirectToRoute('app_login');
    }

        // Récupérer le terme de recherche depuis la requête GET
        $searchTerm = $request->query->get('search', '');

        // Construire la requête de base pour récupérer les séances
        $queryBuilder = $entityManager->getRepository(Sceance::class)->createQueryBuilder('s');
        $queryBuilder
            ->where('s.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur);
        // Ajouter un filtre si un terme de recherche est fourni
        if (!empty($searchTerm)) {
            $queryBuilder
                ->andWhere('s.titre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Pagination avec KnpPaginatorBundle
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Requête Doctrine
            $request->query->getInt('page', 1), // Numéro de page (par défaut : 1)
            10 // Nombre d'éléments par page
        );

        return $this->render('sceance/index.html.twig', [
            'pagination' => $pagination, // Passer la pagination à la vue
            'searchTerm' => $searchTerm, // Passer le terme de recherche à la vue
        ]);
    }




    // #[Route('/sceance', name: 'app_sceance_index')] // Correction : /sceance
    // public function index(EntityManagerInterface $entityManager): Response // Correction : Sceance avec un 'c'
    // {
    //     $sceances = $entityManager->getRepository(Sceance::class)->findAll(); // Correction : Sceance avec un 'c'

    //     return $this->render('sceance/index.html.twig', [ // Correction : templates/sceance/index.html.twig
    //         'sceances' => $sceances, // Correction : sceances avec un 'c'
    //     ]);
    // }
}
