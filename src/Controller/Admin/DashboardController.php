<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Housing;
use App\Entity\Image;
use App\Entity\Status;
use App\Entity\Type;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Accessimmo');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->overrideTemplates([
                'layout' => 'admin/index.html.twig'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('Gestion', 'fa fa-dolly-flatbed');
        yield MenuItem::linkToCrud('Types', 'fa fa-cogs', Type::class);
        yield MenuItem::linkToCrud('Status', 'fa fa-fan', Status::class);
        yield MenuItem::linkToCrud('Adresse', 'fa fa-map-marked', Address::class);
        yield MenuItem::linkToCrud('Images', 'fa fa-image', Image::class);
        yield MenuItem::linkToCrud('Logements', 'fa fa-city', Housing::class);

        yield MenuItem::section('Utilisateurs', 'fa fa-dolly-flatbed');

        yield MenuItem::section('Développeurs');
        yield MenuItem::linkToUrl('Dashboard doc', null, 'https://symfony.com/doc/current/bundles/EasyAdminBundle/dashboards.html');
        yield MenuItem::linkToUrl('CRUD doc', null, 'https://symfony.com/doc/current/bundles/EasyAdminBundle/crud.html');
    }
}
