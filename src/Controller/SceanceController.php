<?php

namespace App\Controller;

use App\Entity\Sceance;
use App\Form\SceanceType;
use App\Entity\ProgrammeSceance;
use App\Repository\SceanceRepository;
use App\Repository\ExerciceRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SceanceController extends AbstractController
{
    // Route pour créer une nouvelle séance
    #[Route('/sceance/new', name: 'app_sceance_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();

        if (!$utilisateur) {
            $this->addFlash('error', 'Vous devez être connecté pour créer une séance.');
            return $this->redirectToRoute('app_login');
        }

        $sceance = new Sceance();
        $sceance->setUtilisateur($utilisateur);

        $form = $this->createForm(SceanceType::class, $sceance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sceance);
            $entityManager->flush();

            $this->addFlash('success', 'Séance créée avec succès !');
            return $this->redirectToRoute('app_sceance_show', ['id' => $sceance->getId()]);
        }
        $form = $this->createForm(SceanceType::class, $sceance, [
            'utilisateur' => $utilisateur, 
        ]);

        return $this->render('sceance/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher une séance
    #[Route('/sceance/{id}', name: 'app_sceance_show')]
    public function show(Sceance $sceance): Response
    {
        $utilisateur = $this->getUser();
        if ($utilisateur !== $sceance->getUtilisateur()) {
            $this->addFlash('error', 'Vous n\'avez pas la permission de voir cette séance.');
            return $this->redirectToRoute('app_sceance_index');
        }

        return $this->render('sceance/show.html.twig', [
            'sceance' => $sceance,
        ]);
    }

    // Route pour éditer une séance
    #[Route('/sceance/{id}/edit', name: 'app_sceance_edit')]
    public function edit(Request $request, Sceance $sceance, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur connecté est le propriétaire de la séance
        $utilisateur = $this->getUser();
        if ($utilisateur !== $sceance->getUtilisateur()) {
            $this->addFlash('error', 'Vous n\'avez pas la permission de modifier cette séance.');
            return $this->redirectToRoute('app_sceance_index');
        }

        $form = $this->createForm(SceanceType::class, $sceance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Séance mise à jour avec succès !');
            return $this->redirectToRoute('app_sceance_show', ['id' => $sceance->getId()]);
        }
        $form = $this->createForm(SceanceType::class, $sceance, [
            'utilisateur' => $utilisateur, // 👈 on passe le user ici
        ]);

        return $this->render('sceance/edit.html.twig', [
            'form' => $form->createView(),
            'sceance' => $sceance,
        ]);
    }

    #[Route('/sceance/{sceanceId}/remove-exercice/{exerciceId}', name: 'app_sceance_remove_exercice', methods: ['POST'])]
public function removeExerciceFromSceance(
    int $sceanceId,
    int $exerciceId,
    SceanceRepository $sceanceRepository,
    ExerciceRepository $exerciceRepository,
    EntityManagerInterface $em,
    Request $request
): Response {
    $sceance = $sceanceRepository->find($sceanceId);
    $exercice = $exerciceRepository->find($exerciceId);

    if (!$sceance || !$exercice) {
        throw $this->createNotFoundException('Exercice ou séance introuvable');
    }

    // Vérification du token CSRF
    if ($this->isCsrfTokenValid('remove_exercice_' . $exercice->getId(), $request->request->get('_token'))) {
        $sceance->removeExercice($exercice);
        $em->flush();
    }

    return $this->redirectToRoute('app_sceance_edit', ['id' => $sceanceId]);
}

    // Route pour supprimer une séance
    #[Route('/sceance/{id}/delete', name: 'app_sceance_delete')]
    public function delete(Request $request, Sceance $sceance, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur connecté est le propriétaire de la séance
        $utilisateur = $this->getUser();
        if ($utilisateur !== $sceance->getUtilisateur()) {
            $this->addFlash('error', 'Vous n\'avez pas la permission de supprimer cette séance.');
            return $this->redirectToRoute('app_sceance_index');
        }

        if ($this->isCsrfTokenValid('delete' . $sceance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sceance);
            $entityManager->flush();

            $this->addFlash('success', 'Séance supprimée avec succès !');
        }

        return $this->redirectToRoute('app_sceance_index');
    }

    // Route pour afficher la liste des séances
    #[Route('/sceance', name: 'app_sceance_index', methods: ['GET'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    ): Response {
        $utilisateur = $this->getUser();

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

    #[Route('/sceance/{sceanceId}/ajouter-au-programme', name: 'app_sceance_ajouter_au_programme')]
public function ajouterAuProgrammeDepuisSceance(
    int $sceanceId,
    Request $request,
    EntityManagerInterface $em,
    SceanceRepository $sceanceRepository,
    ProgrammeRepository $programmeRepository
): Response {
    $sceance = $sceanceRepository->find($sceanceId);
    $utilisateur = $this->getUser();

    if (!$sceance || $sceance->getUtilisateur() !== $utilisateur) {
        $this->addFlash('error', 'Accès interdit à cette séance.');
        return $this->redirectToRoute('app_sceance_index');
    }

    $programmes = $programmeRepository->findBy(['utilisateur' => $utilisateur]);

    if ($request->isMethod('POST')) {
        $programmeId = $request->request->get('programme_id');
        $jour = $request->request->get('jour');

        if (!in_array($jour, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'], true)) {
            $this->addFlash('error', 'Jour invalide.');
        } else {
            $programme = $programmeRepository->find($programmeId);

            if ($programme && $programme->getUtilisateur() === $utilisateur) {
                $programmeSceance = new ProgrammeSceance();
                $programmeSceance->setProgramme($programme);
                $programmeSceance->setSceance($sceance);
                $programmeSceance->setJour($jour);

                $em->persist($programmeSceance);
                $em->flush();

                $this->addFlash('success', 'Séance ajoutée au programme.');
                return $this->redirectToRoute('app_programme_show', ['id' => $programmeId]);
            } else {
                $this->addFlash('error', 'Programme non valide.');
            }
        }
    }

    return $this->render('sceance/ajouter_au_programme.html.twig', [
        'sceance' => $sceance,
        'programmes' => $programmes,
    ]);
}

}
