<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Order;
use App\Entity\Products;
use App\Entity\User;
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
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Site e-commerce');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linktoCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linktoCrud('Orders', 'fa fa-shopping-cart', Order::class)->setController(OrderCrudController::class);
        yield MenuItem::linktoCrud('Categories', 'fa fa-list', Category::class);
        yield MenuItem::linktoCrud('Products', 'fa fa-tag', Products::class);
        yield MenuItem::linktoCrud('Carriers', 'fa fa-truck', Carrier::class);
        yield MenuItem::linktoCrud('Headers', 'fa fa-desktop', Header::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
