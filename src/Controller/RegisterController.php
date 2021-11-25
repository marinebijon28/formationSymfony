<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/register', name: 'register')]
    public function index(Request $request, HasherUserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $notification = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $searchEmail = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$searchEmail)
            {
                $password = $user->getPassword();
                $user->setPassword($encoder->hashPassword($user, $password));
                

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = "Bonjour " . $user->getFirstName() . "<br/>Bienvenue sur le site ecommerce<br/>";
                $mail->send($user->getEmail(), $user->getFirstname() . " " . $user->getLastname(), 
                            "Inscription au site ecommerce", $content);

                $notification = "Votre inscription s'est correctement déroulée. 
                Vous pensez dès à présent vous connecter à votre compte";
            }
            else
                $notification = "L'email que vous renseigné existe déjà.";

        }
        

        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
