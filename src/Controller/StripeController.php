<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use \Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/stripe/{id}', name: 'stripe')]
    public function index($id, EntityManagerInterface $entityManager): Response
    {

        $producForStripe = [];

        $yourDomain = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($id);

        if (!$order)
            new JsonResponse(['error' => 'order']);

        foreach ($order->getOrderDetails()->getValues() as $product)
        {
            $illustration = $entityManager->getRepository(Products::class)->findOneBy(['name' => $product->getProduct()]);
            $producForStripe[] = [
                'price_data' => [
                    'unit_amount' => $product->getPrice(),
                    'currency' => 'eur',
                    'product_data' =>  [
                        'name' => $product->getProduct(),
                        'images' => [$yourDomain . "/assets/productsImgs/" . $illustration->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $producForStripe[] = [
            'price_data' => [
                'unit_amount' => $order->getCarrierPrice(),
                'currency' => 'eur',
                'product_data' =>  [
                    'name' => $order->getCarrierName(),
                    'images' => [$yourDomain],
                ],
            ],
            'quantity' => 1,
        ];


        Stripe::setApiKey('sk_test_51JzGttCUvPaaE3TrcWQ6sGVa37wxX6lzTKWew8jnsUTSrStRFfU5fYKTphmkE4IR7hGDw9bBfqAvO0MQVYkumZ4v00YjAQ9n8d');

        header('Content-Type: application/json');

        $checkoutSession = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
        // 'payment_method_types' => ['card'],
            'line_items' => [$producForStripe],
            'mode' => 'payment',
            // 'success_url' => $yourDomain . '/order/success/' . $id,
            // 'cancel_url' => $yourDomain . '/order/cancel/' . $id,
            'success_url' => $yourDomain . '/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $yourDomain . '/order/cancel/{CHECKOUT_SESSION_ID}',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkoutSession->url);
    
        $order->setStripeSessionId($checkoutSession->id);
        $entityManager->flush();

        $response = new JsonResponse(['id' => $checkoutSession->id]);
        return $response;
    }
}
