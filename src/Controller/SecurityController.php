<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/register', name: 'security_register')]
    public function register(
        UserPasswordHasherInterface $hasher,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $hasher->hashPassword($user, $form->get('password')->getData())
            );

            $em->persist($user);
            $em->flush();

            return $this->redirect('/');
        }

        return $this->renderForm('security/register.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/login', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    #[Route('/logout', name: 'security_logout')]
    public function logout(): void
    {
        // empty on purpose.
    }
}
