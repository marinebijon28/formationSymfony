<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/address', name: 'accountAddress')]
    public function index(): Response
    {
        return $this->render('account/accountAddress.html.twig', [
            'addresses' => $this->entityManager->getRepository(Address::class)->findAll(),
        ]);
    }

    #[Route('/account/add/address', name: 'accountAddAddress')]
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if ($cart->get())
                return $this->redirectToRoute('order');
            else
                return $this->redirectToRoute('accountAddress');
        }

        return $this->render('account/accountFormAddress.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/edit/address/{id}', name: 'accountEditAddress')]
    public function edit($id, Request $request): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if (!$address || $address->getUser() != $this->getUser())
            return $this->redirectToRoute('accountAddress');

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            return $this->redirectToRoute('accountAddress');
        }

        return $this->render('account/accountFormAddress.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/remove/address/{id}', name: 'accountRemoveAddress')]
    public function remove($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
      
        if ($address || $address->getUser() == $this->getUser())
        {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('accountAddress');
    }

}
