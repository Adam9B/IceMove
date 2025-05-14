<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProgrammesceanceController extends AbstractController
{
    #[Route('/programmesceance', name: 'app_programmesceance')]
    public function index(): Response
    {
        return $this->render('programmesceance/index.html.twig', [
            'controller_name' => 'ProgrammesceanceController',
        ]);
    }
}
