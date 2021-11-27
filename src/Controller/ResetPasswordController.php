<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordUpdateType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reset/password', name: 'resetPassword')]
    public function index(Request $request): Response
    {
        if ($this->getUser())
            return $this->redirectToRoute('home');

        if ($request->get('email'))
        {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user)
            {
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new DateTimeImmutable());
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                $url = "http://127.0.0.1:8000" . $this->generateUrl('updateMailPassword', ['token' => $resetPassword->getToken()]);
             
                $mail = new Mail();
                $content = "Bonjour " . $user->getFirstName() . 
                            "<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site ecommerce<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'> mettre à jour votre mot de passe</a>.";
                $mail->send($user->getEmail(), $user->getFirstname() . " " . $user->getLastname(), 
                            "Réinitialiser votre mot de passe sur le site ecommerce", $content);
            $this->addFlash('notice', 'Vous allez recevoir un email pour reset votre mot de passe');
        }
        else
            $this->addFlash('notice', 'Votre amil n\'a pas de compte associé');
        }
       
        return $this->render('resetPassword/resetPassword.html.twig');
    }

    #[Route('/reset/update/password/{token}', name: 'updateMailPassword')]
    public function updateMail($token, Request $request,UserPasswordHasherInterface $encoder): Response
    {
        $resetPassword =$this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$resetPassword) {
           return $this->redirectToRoute('resetPassword');
        }
        $now = new DateTimeImmutable();

        if ($now > $resetPassword->getCreatedAt()->modify("+3 hour"))
        {
            $this->addFlash('notice', 'Votre demande de mot de passes à expiré. Merci de renouveller.');
            return $this->redirectToRoute("resetPassword");
        }
        
        $form = $this->createForm(ResetPasswordUpdateType::class);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $newPwd = $form->get('password')->getData();
            $hashpassword = $encoder->hashPassword($resetPassword->getUser(), $newPwd);
            $resetPassword->getUser()->setPassword($hashpassword);
            $this->entityManager->flush();

            $this->addFlash(
               'notice',
               'Votre mot de passes a bien été mis à jour'
            );
            return $this->redirectToRoute('login');
        }

        return $this->render('resetPassword/updatePassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
