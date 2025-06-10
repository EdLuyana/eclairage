<?php

namespace App\Controller\user;

use App\Repository\ProductVariantRepository;
use App\Service\UserContextService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/user', name: 'user_')]
class UserStockController extends AbstractController
{
    #[Route('/sell', name: 'sell')]
    public function sell(Request $request, SessionInterface $session, ProductVariantRepository $variantRepo, LocationRepository $locationRepository, EntityManagerInterface $em, UserContextService $userContext): Response
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
    public function checkStock(Request $request, UserContextService $userContext, ProductVariantRepository $variantRepo, LocationRepository $locationRepo): Response
    {
        // 1. Récupération du magasin courant
        try {
            $location = $userContext->ensureCurrentLocation();
        } catch (\RuntimeException) {
            return $this->redirectToRoute('user_select_location');
        }

        $results = null; // pour le rendu

        // 2. Traitement du formulaire
        if ($request->isMethod('POST')) {
            $reference = trim($request->request->get('reference'));
            $size      = trim($request->request->get('size'));

            $variant = $variantRepo->findOneByReferenceAndSize($reference, $size);

            if (!$variant) {
                $this->addFlash('error', 'Produit ou taille introuvable.');
            } else {
                // Préparer le tableau des stocks par magasin
                $results = [];
                $locations = $locationRepo->findAll();
                foreach ($locations as $loc) {
                    $stock = $variant->getStockFor($loc);
                    $qty   = $stock ? $stock->getQuantity() : 0;
                    $results[] = [
                        'location' => $loc->getName(),
                        'quantity' => $qty,
                    ];
                }
            }
        }

        return $this->render('user/stock/check_stock.html.twig', [
            'location' => $location,
            'results'  => $results,
        ]);
    }

    #[Route('/add-stock', name: 'add_stock')]
    public function addStock(Request $request, UserContextService $userContext, ProductVariantRepository $variantRepo, EntityManagerInterface $em): Response
    {
        $location = $userContext->getCurrentLocation();

        if ($request->isMethod('POST')) {
            $reference = trim($request->request->get('reference'));
            $size = trim($request->request->get('size'));
            $quantity = (int) $request->request->get('quantity');

            $variant = $variantRepo->findOneByReferenceAndSize($reference, $size);

            if (!$variant) {
                $this->addFlash('error', 'Produit ou taille introuvable.');
            } elseif ($quantity <= 0) {
                $this->addFlash('error', 'Quantité invalide.');
            } else {
                $stock = $variant->getStockFor($location);
                if (!$stock) {
                    $stock = new Stock();
                    $stock->setVariant($variant);
                    $stock->setLocation($location);
                    $stock->setQuantity($quantity);
                    $em->persist($stock);
                } else {
                    $stock->setQuantity($stock->getQuantity() + $quantity);
                }

                $em->flush();

                $this->addFlash('success', sprintf(
                    '%d unité(s) ajoutée(s) pour %s (%s). Stock total : %d',
                    $quantity,
                    $variant->getProduct()->getName(),
                    $variant->getSize(),
                    $stock->getQuantity()
                ));
            }
        }

        return $this->render('user/stock/add_stock.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/get-sizes', name: 'user_get_sizes', methods: ['GET'])]
    public function getSizes(Request $request, ProductRepository $productRepo): JsonResponse
    {
        $reference = $request->query->get('reference');

        if (!$reference) {
            return new JsonResponse(['error' => 'Aucune référence fournie.'], 400);
        }

        $product = $productRepo->findOneBy(['reference' => $reference]);

        if (!$product) {
            return new JsonResponse(['sizes' => []]); // produit inconnu
        }

        $sizes = $product->getVariants()->map(fn($variant) => $variant->getSize())->toArray();

        return new JsonResponse(['sizes' => $sizes]);
    }
}
