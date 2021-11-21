<?php

namespace App\Controller;

use App\Class\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(Cart $cart): Response
    {   
        $cart = $cart->getFull();

        return $this->render('cart/cart.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cartAdd')]
    public function cartAdd($id, Cart $cart): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/sub/{id}', name: 'cartSub')]
    public function cartSub($id, Cart $cart): Response
    {
        $cart->sub($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'cartRemove')]
    public function cartRemove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }

    #[Route('/cart/delete/{id}', name: 'cartDelete')]
    public function cartDelete($id, Cart $cart): Response
    {
        $cart->delete($id);
        
        return $this->redirectToRoute('cart');
    }
}
