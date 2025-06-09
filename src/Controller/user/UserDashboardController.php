<?php

namespace App\Controller\user;

use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'user_dashboard')]
    public function index(SessionInterface $session, LocationRepository $locationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $locationId = $session->get('selected_location_id');

        if (!$locationId) {
            return $this->redirectToRoute('user_select_location');
        }

        $location = $locationRepository->find($locationId);

        if (!$location) {
            // Cas où l'ID en session ne correspond plus à un magasin valide
            $session->remove('selected_location_id');
            return $this->redirectToRoute('user_select_location');
        }

        return $this->render('user/dashboard.html.twig', [
            'location' => $location
        ]);
    }
}
