<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // on modifie le chemin de la page d'accueil de l'admin
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mobile World - Administration');
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        // Lien vers le site
        yield MenuItem::linkToCrud('Article du blog', 'fas fa-list', Article::class);
        yield MenuItem::linktoRoute('Retour vers le site', 'fas fa-home', 'app_accueil');
        // Relier les CRUDs
        
    }
}