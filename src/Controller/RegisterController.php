<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface  $passwordHasher,
        AuthenticationUtils $authenticationUtils,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $notification = null;
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $em->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$search_email) {
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
                $em->persist($user);
                $em->flush();
                $notification = "Merci pour votre inscription, vous pouvez à présent vous connecter.";

                // $mail = new Mail($mailer);
                // $mail->send($user->getEmail());
            } else {
                $notification = "L'email est déjà enregistrée !";
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
