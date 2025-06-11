<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Location;
use App\Entity\StockMovement;
use App\Entity\Product;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\UserCreateForm;
use App\Form\UserPasswordEditForm;


#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/create', name: 'admin_create')]
    public function create(): Response
    {
        return $this->render('admin/create.html.twig');
    }

    #[Route('/stock', name: 'admin_stock_overview')]
    public function stockOverview(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $locationId = $request->query->get('location');

        // Récupération des emplacements pour le select
        $locations = $em->getRepository(Location::class)->findAll();

        // Construction de la requête principale
        $qb = $em->createQueryBuilder();
        $qb->select('p', 'v', 's', 'b', 'l')
            ->from(Product::class, 'p')
            ->join('p.brand', 'b')
            ->join('p.variants', 'v')
            ->join('v.stocks', 's')
            ->join('s.location', 'l')
            ->where('s.quantity >= 0');

        if ($locationId) {
            $qb->andWhere('l.id = :locationId')
                ->setParameter('locationId', $locationId);
        }

        $productsData = $qb->getQuery()->getResult();

        // Récupérer les ventes des 30 derniers jours (à affiner selon ton entité réelle)
        $date = new \DateTimeImmutable('-30 days');

        $salesData = $em->createQueryBuilder()
            ->select('IDENTITY(sm.variant) as variant_id', 'IDENTITY(sm.location) as location_id', 'SUM(sm.quantity) as sales')
            ->from(StockMovement::class, 'sm')
            ->where('sm.createdAt >= :date')
            ->setParameter('date', $date)
            ->groupBy('variant_id', 'location_id')
            ->getQuery()
            ->getResult();

// Indexation pour accès rapide
        $salesIndex = [];
        foreach ($salesData as $sale) {
            $key = $sale['variant_id'] . '-' . $sale['location_id'];
            $salesIndex[$key] = $sale['sales'];
        }

        return $this->render('admin/stock.html.twig', [
            'locations' => $locations,
            'selectedLocationId' => $locationId,
            'productsData' => $productsData,
            'salesIndex' => $salesIndex,
        ]);
    }

    #[Route('/sales', name: 'admin_sales_journal')]
    public function sales(): Response
    {
        return new Response('<h1>Journal des ventes (à implémenter)</h1>');
    }

    #[Route('/admin/users', name: 'admin_manage_users')]
    public function manageUsers(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(UserCreateForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            $user = new User();
            $user->setUsername($username);
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $user->setArchived(false);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Compte vendeuse créé avec succès.');
            return $this->redirectToRoute('admin_manage_users');
        }

        $activeUsers = $em->getRepository(User::class)->findBy(['archived' => false]);
        $archivedUsers = $em->getRepository(User::class)->findBy(['archived' => true]);

        return $this->render('admin/users.html.twig', [
            'form' => $form->createView(),
            'activeUsers' => $activeUsers,
            'archivedUsers' => $archivedUsers,
        ]);
    }

    #[Route('/labels', name: 'admin_generate_labels')]
    public function labels(): Response
    {
        return new Response('<h1>Génération des étiquettes (à implémenter)</h1>');
    }

    #[Route('/import', name: 'admin_import_csv')]
    public function import(): Response
    {
        return new Response('<h1>Importation CSV (à implémenter)</h1>');
    }

    #[Route('/admin/user/{id}/archive', name: 'admin_user_archive')]
    public function archiveUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setArchived(true);
        $em->flush();
        $this->addFlash('success', 'Vendeuse archivée.');
        return $this->redirectToRoute('admin_manage_users');
    }

    #[Route('/admin/user/{id}/reactivate', name: 'admin_user_reactivate')]
    public function reactivateUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setArchived(false);
        $em->flush();
        $this->addFlash('success', 'Vendeuse réactivée.');
        return $this->redirectToRoute('admin_manage_users');
    }

    #[Route('/admin/user/{id}/edit-password', name: 'admin_user_edit_password')]
    public function editPassword(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(UserPasswordEditForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('plainPassword')->getData();
            $hashed = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashed);
            $em->flush();

            $this->addFlash('success', 'Mot de passe mis à jour.');
            return $this->redirectToRoute('admin_manage_users');
        }

        return $this->render('admin/edit_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
