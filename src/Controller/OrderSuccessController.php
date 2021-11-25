<?php

namespace App\Controller;

use App\Class\Cart;
use App\Class\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/order/success/{stripeSessionId}', name: 'orderSuccess')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $this->getUser() != $order->getUser())
            return $this->redirectToRoute('home');

        if (!$order->getIsPaid())
        {
            $cart->remove();

            $order->setIsPaid(1);
            $this->entityManager->flush();

            $mail = new Mail();
            $content = "Bonjour " . $order->getUser()->getFirstName() . "<br/>Merci pour votre commande<br/>";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname() . " " . $order->getUser()->getLastname(), 
                        "Merci pour votre commande le site ecommerce", $content);
        }
            

        return $this->render('order/success.html.twig',
        [
            'order' => $order,
            'carrierName' => $order->getCarrierName(),
        ]);
    }
}
