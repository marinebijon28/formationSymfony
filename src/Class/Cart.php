<?php

namespace App\Class;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart {
    private $session;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function getFull()
    {
        $cart = $this->session->get('cart');
        $cartComplete = [];

        if (!empty($cart))
            foreach ($cart as $id => $quantity)
            {
                $product = $this->entityManager->getRepository(Products::class)->findOneById($id);
                if ($product)
                    $cartComplete[] = [
                        'product' => $product,
                        'quantity' => $quantity
                    ];
                else
                    $this->delete($id);
            }
        return $cartComplete;
    }

    public function get() {
        return $this->session->get('cart');
    }

    public function remove() {
        return $this->session->remove('cart');
    }

    public function add($id) 
    {
        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        }
        else
        {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function sub($id) 
    {
        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id]) &&$cart[$id] > 1)
        {
            $cart[$id]--;
        }
        else
        {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function delete($id) {
        $cart = $this->session->get('cart');

        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }
}

?>