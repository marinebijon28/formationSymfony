<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/password', name: 'accountPassword')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $oldPassword = $form->get('oldPassword')->getData();

            if ($encoder->isPasswordValid($user, $oldPassword)) 
            {
                $newPassword = $form->get('newPassword')->getData();
                $user->setPassword($encoder->hashPassword($user, $newPassword));
                $this->entityManager->flush();
                $notification = "Votre mot de passe a bien été modifié";
            }
            else 
            {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
            
        }

        return $this->render('account/accountPassword.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
