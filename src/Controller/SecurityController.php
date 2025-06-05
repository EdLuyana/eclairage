<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($user = $this->getUser()) {
            $roles = $user->getRoles();

            if (in_array('ROLE_ADMIN', $roles, true)) {
                return $this->redirectToRoute('admin_dashboard');
            } elseif (in_array('ROLE_USER', $roles, true)) {
                return $this->redirectToRoute('user_dashboard');
            }

            // par défaut, si aucun rôle ne correspond
            return $this->redirectToRoute('app_login');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony intercepte cette route
        throw new \LogicException('Cette méthode peut rester vide, elle est interceptée par le firewall.');
    }
}
