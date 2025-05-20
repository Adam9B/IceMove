<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\Utilisateur;

use App\Form\ProgrammeType;

use App\Entity\ProgrammeSceance;
use App\Repository\SceanceRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/programme')]
class ProgrammeController extends AbstractController
{
    #[Route('/', name: 'app_programme_index', methods: ['GET'])]
    public function index(ProgrammeRepository $programmeRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $this->getUtilisateur();
        if (!$utilisateur) {
            $this->addFlash('error', 'Vous devez être connecté');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('programme/index.html.twig', [
            'programmes' => $programmeRepository->findBy(['utilisateur' => $utilisateur]),
        ]);
    }

    private function getUtilisateur(): ?Utilisateur
    {
        return $this->getUser() instanceof Utilisateur ? $this->getUser() : null;
    }

    #[Route('/new', name: 'app_programme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $programme = new Programme();
        $programme->setUtilisateur($this->getUtilisateur());

        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($programme);
            $em->flush();

            return $this->redirectToRoute('app_programme_index');
        }

        return $this->render('programme/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_programme_show', methods: ['GET'])]
    public function show(Programme $programme): Response
    {
        $joursOrdre = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        $programmeSceances = $programme->getProgrammeSceances()->toArray();

        usort($programmeSceances, function ($a, $b) use ($joursOrdre) {
            return array_search($a->getJour(), $joursOrdre) <=> array_search($b->getJour(), $joursOrdre);
        });

        return $this->render('programme/show.html.twig', [
            'programme' => $programme,
        ]);
    }

    #[Route('/programme/{id}/modifier', name: 'app_programme_edit')]
    public function edit(Request $request, Programme $programme, EntityManagerInterface $em): Response
    {
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
    
            // Modifier les infos du programme
            $programme->setTitre($data['titre'] ?? '');
            $programme->setDescription($data['description'] ?? '');
    
            // Modifier ou supprimer les séances liées
            foreach ($programme->getProgrammeSceances() as $ps) {
                $id = $ps->getId();
    
                if (isset($data['delete'][$id])) {
                    $em->remove($ps);
                    continue;
                }
    
                if (isset($data['jour'][$id])) {
                    $ps->setJour($data['jour'][$id]);
                }
            }
    
            $em->flush();
            $this->addFlash('success', 'Programme mis à jour.');
    
            return $this->redirectToRoute('app_programme_show', ['id' => $programme->getId()]);
        }
    
        return $this->render('programme/edit.html.twig', [
            'programme' => $programme,
            'jourOptions' => $jours,
        ]);
    }





    #[Route('/{id}', name: 'app_programme_delete', methods: ['POST'])]
    public function delete(Request $request, Programme $programme, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $programme->getId(), $request->request->get('_token'))) {
            $em->remove($programme);
            $em->flush();
        }

        return $this->redirectToRoute('app_programme_index');
    }

    #[Route('/programme/{id}/ajouter-sceance', name: 'app_programme_ajouter_sceance')]
    public function ajouterSceance(
        Request $request,
        Programme $programme,
        SceanceRepository $sceanceRepository,
        EntityManagerInterface $em
    ): Response {
        if ($programme->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit de modifier ce programme.");
        }

        // Toutes les séances de l'utilisateur
        $sceancesDisponibles = $sceanceRepository->createQueryBuilder('s')
            ->where('s.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $this->getUser())
            ->getQuery()
            ->getResult();

        if ($request->isMethod('POST')) {
            $sceanceId = $request->request->get('sceance_id');
            $jour = $request->request->get('jour'); // À récupérer dans ton formulaire

            $sceance = $sceanceRepository->find($sceanceId);

            $jour = $request->request->get('jour');
            $joursValides = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

            if (!in_array($jour, $joursValides, true)) {
                $this->addFlash('error', 'Veuillez sélectionner un jour valide.');
                return $this->redirectToRoute('app_programme_ajouter_sceance', ['id' => $programme->getId()]);
            }

            // Sécurité
            if ($sceance && $sceance->getUtilisateur() === $this->getUser()) {
                $programmeSceance = new ProgrammeSceance();
                $programmeSceance->setProgramme($programme);
                $programmeSceance->setSceance($sceance);
                $programmeSceance->setJour($jour ?? 'Non défini');

                $em->persist($programmeSceance);
                $em->flush();

                $this->addFlash('success', 'Séance ajoutée au programme.');
                return $this->redirectToRoute('app_programme_show', ['id' => $programme->getId()]);
            } else {
                $this->addFlash('error', 'Vous ne pouvez ajouter que vos propres séances.');
            }
        }

        return $this->render('programme/ajouter_sceance.html.twig', [
            'programme' => $programme,
            'sceances' => $sceancesDisponibles,
        ]);
    }
}
