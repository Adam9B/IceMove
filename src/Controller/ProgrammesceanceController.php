<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Repository\ProgrammeSceanceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProgrammesceanceController extends AbstractController
{
    #[Route('/programme/{id}', name: 'app_programme_show')]
public function show(
    Programme $programme,
    ProgrammeSceanceRepository $programmeSceanceRepository
): Response {
    $sceancesAssociees = $programmeSceanceRepository->findSceancesByProgramme($programme->getId());

    return $this->render('programme/show.html.twig', [
        'programme' => $programme,
        'sceancesAssociees' => $sceancesAssociees,
    ]);
}

}
