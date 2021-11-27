<?php

namespace App\Controller;

use App\Class\Search;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/products', name: 'products')]
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
        { 
            $search = $form->getData();
            $products = $this->entityManager->getRepository(Products::class)->findBySearch($search);
        }
        else 
        {
            $products = $this->entityManager->getRepository(Products::class)->findAll();
        }

        return $this->render('products/products.html.twig', 
        [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{slug}', name: 'product')]
    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Products::class)->findOneBySlug($slug);
        $productsBest = $this->entityManager->getRepository(Products::class)->findByIsBest(1);
        
        if (!$product)
            return $this->redirectToRoute('products');
        return $this->render('products/product.html.twig', 
        [
            'product' => $product,
            'productsBest' => $productsBest,
        ]);
    }
}
