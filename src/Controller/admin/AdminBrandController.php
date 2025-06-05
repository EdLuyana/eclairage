<?php

namespace App\Controller\admin;

use App\Entity\Brand;
use App\Form\BrandForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/brand')]
class AdminBrandController extends AbstractController
{
    #[Route('/', name: 'admin_brand_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $brands = $em->getRepository(Brand::class)->findAll();

        return $this->render('admin/brand/index.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/new', name: 'admin_brand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $brand = new Brand();
        $form = $this->createForm(BrandForm::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($brand);
            $em->flush();
            $this->addFlash('success', 'Marque créée avec succès.');
            return $this->redirectToRoute('admin_brand_index');
        }

        return $this->render('admin/brand/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_brand_edit', methods: ['GET', 'POST'])]
    public function edit(Brand $brand, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(BrandForm::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Marque modifiée avec succès.');
            return $this->redirectToRoute('admin_brand_index');
        }

        return $this->render('admin/brand/edit.html.twig', [
            'form' => $form->createView(),
            'brand' => $brand,
        ]);
    }

    #[Route('/{id}', name: 'admin_brand_delete', methods: ['POST'])]
    public function delete(Request $request, Brand $brand, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $em->remove($brand);
            $em->flush();
            $this->addFlash('success', 'Marque supprimée avec succès.');
        }

        return $this->redirectToRoute('admin_brand_index');
    }
}
