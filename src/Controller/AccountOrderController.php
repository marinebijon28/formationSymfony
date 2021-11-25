<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/order', name: 'accountOrder')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());

        return $this->render('account/accountOrder.html.twig', [
           'orders' => $orders,
        ]);
    }

    #[Route('/account/order/show/{reference}', name: 'accountOrderShow')]
    public function show($reference): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $this->getUser() != $order->getUser())
            return $this->redirectToRoute('accountOrder');

        return $this->render('account/accountOrderShow.html.twig', [
           'order' => $order,
        ]);
    }
}
