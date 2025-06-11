<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/create', name: 'admin_create')]
    public function create(): Response
    {
        return $this->render('admin/create.html.twig');
    }

    #[Route('/stock', name: 'admin_stock_overview')]
    public function stock(): Response
    {
        return new Response('<h1>Page de consultation du stock (à implémenter)</h1>');
    }

    #[Route('/sales', name: 'admin_sales_journal')]
    public function sales(): Response
    {
        return new Response('<h1>Journal des ventes (à implémenter)</h1>');
    }

    #[Route('/users', name: 'admin_manage_users')]
    public function users(): Response
    {
        return new Response('<h1>Gestion des vendeuses (à implémenter)</h1>');
    }

    #[Route('/labels', name: 'admin_generate_labels')]
    public function labels(): Response
    {
        return new Response('<h1>Génération des étiquettes (à implémenter)</h1>');
    }

    #[Route('/import', name: 'admin_import_csv')]
    public function import(): Response
    {
        return new Response('<h1>Importation CSV (à implémenter)</h1>');
    }
}
