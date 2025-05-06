<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Entity\Sceance;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class ExerciceController extends AbstractController
{
    #[Route('/exercices', name: 'app_exercices')]
    public function index(
        Request $request,
        ExerciceRepository $exerciceRepository,
        PaginatorInterface $paginator
    ): Response {
        $bodyPart = $request->query->get('bodyPart');
        $equipment = $request->query->get('equipment');
        $target = $request->query->get('target');

        $queryBuilder = $exerciceRepository->createQueryBuilder('e');

        if ($bodyPart) {
            $queryBuilder->andWhere('e.bodyPart = :bodyPart')->setParameter('bodyPart', $bodyPart);
        }

        if ($equipment) {
            $queryBuilder->andWhere('e.equipment = :equipment')->setParameter('equipment', $equipment);
        }

        if ($target) {
            $queryBuilder->andWhere('e.target = :target')->setParameter('target', $target);
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        $bodyParts = $exerciceRepository->createQueryBuilder('e')
            ->select('DISTINCT e.bodyPart')->getQuery()->getResult();

        $equipmentsQuery = $exerciceRepository->createQueryBuilder('e')
            ->select('DISTINCT e.equipment');
        $targetsQuery = $exerciceRepository->createQueryBuilder('e')
            ->select('DISTINCT e.target');

        if ($bodyPart) {
            $equipmentsQuery->andWhere('e.bodyPart = :bodyPart')->setParameter('bodyPart', $bodyPart);
            $targetsQuery->andWhere('e.bodyPart = :bodyPart')->setParameter('bodyPart', $bodyPart);
        }

        $equipments = $equipmentsQuery->getQuery()->getResult();
        $targets = $targetsQuery->getQuery()->getResult();

        return $this->render('exercice/index.html.twig', [
            'pagination' => $pagination,
            'selectedBodyPart' => $bodyPart,
            'selectedEquipment' => $equipment,
            'selectedTarget' => $target,
            'bodyParts' => array_column($bodyParts, 'bodyPart'),
            'equipments' => array_column($equipments, 'equipment'),
            'targets' => array_column($targets, 'target'),
        ]);
    }

    #[Route('/exercice/{id}/ajouter-a-seance', name: 'exercice_ajouter_a_seance')]
    public function ajouterASceance(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        $exercice = $em->getRepository(Exercice::class)->find($id);
        if (!$exercice) {
            throw $this->createNotFoundException('Exercice non trouvé.');
        }

        $utilisateur = $security->getUser();
        if (!$utilisateur) {
            $this->addFlash('error', 'Vous devez être connecté pour faire cela.');
            return $this->redirectToRoute('app_login');
        }

        $mesSceances = $em->getRepository(Sceance::class)->findBy([
            'utilisateur' => $utilisateur
        ]);

        if ($request->isMethod('POST')) {
            $sceanceId = $request->request->get('sceance_id');
            $sceance = $em->getRepository(Sceance::class)->find($sceanceId);

            if ($sceance && $sceance->getUtilisateur() === $utilisateur) {
                $sceance->addExercice($exercice);
                $em->flush();

                $this->addFlash('success', 'Exercice ajouté à votre séance !');
                return $this->redirectToRoute('app_sceance_show', ['id' => $sceance->getId()]);
            }

            $this->addFlash('error', 'Séance invalide.');
        }

        return $this->render('exercice/ajouter_a_seance.html.twig', [
            'exercice' => $exercice,
            'mesSceances' => $mesSceances,
        ]);
    }
}
