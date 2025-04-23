<?php

// src/Controller/ExerciceController.php

namespace App\Controller;

use App\Repository\ExerciceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciceController extends AbstractController
{
    #[Route('/exercices', name: 'app_exercices')]
    public function index(
        Request $request,
        ExerciceRepository $exerciceRepository,
        PaginatorInterface $paginator
    ): Response {
        // Récupérer les filtres depuis la requête GET
        $bodyPart = $request->query->get('bodyPart');
        $equipment = $request->query->get('equipment');
        $target = $request->query->get('target');

        // Construire la requête pour récupérer les exercices
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

        // Pagination (10 exercices par page)
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // Numéro de page (par défaut : 1)
            10 // Nombre d'exercices par page
        );

        // Récupérer les options de filtre disponibles
        $bodyParts = $exerciceRepository->createQueryBuilder('e')
            ->select('DISTINCT e.bodyPart')
            ->getQuery()
            ->getResult();

        // Filtrer les équipements et cibles en fonction du groupe musculaire sélectionné
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
}





// #[Route('/exercice', name: 'app_exercice')]
//     public function index( ExerciceRepository $exerciceRepository): Response
//     {

//         $exercices = $exerciceRepository->findAll();


//         return $this->render('exercice/index.html.twig', [
//             'exercices' => $exercices,
//         ]);
//     }