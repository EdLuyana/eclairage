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

#[Route('/user', name: 'user_')]
class UserStockController extends AbstractController
{
    #[Route('/sell', name: 'sell')]
    public function sell(Request $request, UserContextService $userContext, ProductVariantRepository $variantRepo, EntityManagerInterface $em): Response
    {
        try {
            $location = $userContext->ensureCurrentLocation();
        } catch (\RuntimeException) {
            return $this->redirectToRoute('user_select_location');
        }

        if ($request->isMethod('POST')) {
            $reference = trim($request->request->get('reference'));
            $size = trim($request->request->get('size'));

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
                    $stock->setQuantity($stock->getQuantity() - 1);
                    $em->flush();

                    $this->addFlash('success', sprintf(
                        'Vente enregistrée. Stock restant pour %s (%s) : %d',
                        $variant->getProduct()->getName(),
                        $variant->getSize(),
                        $stock->getQuantity()
                    ));
                }
            }
        }

        return $this->render('user/stock/sell.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/check-stock', name: 'check_stock')]
    public function checkStock(
        UserContextService $userContext
    ): Response {
        try {
            $location = $userContext->ensureCurrentLocation();
        } catch (\RuntimeException) {
            return $this->redirectToRoute('user_select_location');
        }

        return $this->render('user/stock/check_stock.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/add-stock', name: 'add_stock')]
    public function addStock(
        UserContextService $userContext
    ): Response {
        try {
            $location = $userContext->ensureCurrentLocation();
        } catch (\RuntimeException) {
            return $this->redirectToRoute('user_select_location');
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
