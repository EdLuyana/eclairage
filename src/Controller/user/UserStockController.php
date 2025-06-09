<?php

namespace App\Controller\user;

use App\Repository\ProductVariantRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/user', name: 'user_')]
class UserStockController extends AbstractController
{
    #[Route('/sell', name: 'sell')]
    public function sell(Request $request, SessionInterface $session, ProductVariantRepository $variantRepo, LocationRepository $locationRepository, EntityManagerInterface $em): Response
    {
        $locationId = $session->get('selected_location_id');

        if (!$locationId) {
            return $this->redirectToRoute('user_select_location');
        }

        $location = $locationRepository->find($locationId);

        if (!$location) {
            $session->remove('selected_location_id');
            return $this->redirectToRoute('user_select_location');
        }

        if ($request->isMethod('POST')) {
            $reference = trim($request->request->get('reference'));
            $size = trim($request->request->get('size'));

            // On récupère la variante correspondante
            $variant = $variantRepo->findOneByReferenceAndSize($reference, $size);

            if (!$variant) {
                $this->addFlash('error', 'Produit ou taille introuvable.');
            } else {
                $stock = $variant->getStockFor($location);
                if (!$stock) {
                    $this->addFlash('error', 'Aucun stock pour ce produit dans ce magasin.');
                } elseif ($stock->getQuantity() <= 0) {
                    $this->addFlash('error', 'Stock insuffisant.');
                } else {
                    $newQty = $stock->getQuantity() - 1;
                    $stock->setQuantity($newQty);
                    $em->flush();

                    $this->addFlash('success', sprintf(
                        'Vente enregistrée. Stock restant pour %s (%s) : %d',
                        $variant->getProduct()->getName(),
                        $variant->getSize(),
                        $newQty
                    ));
                }
            }
        }

        return $this->render('user/stock/sell.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/check-stock', name: 'check_stock')]
    public function checkStock(Request $request, SessionInterface $session): Response
    {
        // Recherche de disponibilité ailleurs
        return $this->render('user_stock/check_stock.html.twig', [
            'location' => $session->get('location'),
        ]);
    }

    #[Route('/add-stock', name: 'add_stock')]
    public function addStock(Request $request, SessionInterface $session): Response
    {
        // Ajout de produit au stock du magasin courant
        return $this->render('user_stock/add_stock.html.twig', [
            'location' => $session->get('location'),
        ]);
    }
}
