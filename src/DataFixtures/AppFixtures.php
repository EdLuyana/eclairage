<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Location;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\Stock;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Catégories
        $cat1 = (new Category())->setName('Ampoules');
        $cat2 = (new Category())->setName('Lampes');
        $manager->persist($cat1);
        $manager->persist($cat2);

        // Marques
        $brand1 = (new Brand())->setName('Philips');
        $brand2 = (new Brand())->setName('Osram');
        $manager->persist($brand1);
        $manager->persist($brand2);

        // Magasins
        $mag1 = (new Location())->setName('Magasin Paris');
        $mag2 = (new Location())->setName('Magasin Lyon');
        $manager->persist($mag1);
        $manager->persist($mag2);

        // Produits
        $prod1 = (new Product())
            ->setName('Ampoule LED 60W')
            ->setBrand($brand1)
            ->setCategory($cat1)
            ->setColor('Blanc')
            ->setPrice(29)
            ->setReference('prod1')
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($prod1);

        $prod2 = (new Product())
            ->setName('Lampe de bureau')
            ->setBrand($brand2)
            ->setCategory($cat2)
            ->setColor('Noir')
            ->setPrice(49)
            ->setReference('prod2')
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($prod2);

        // Variantes (en liant bien les deux côtés de la relation)
        $variant1_s = (new ProductVariant())->setSize('S');
        $variant1_m = (new ProductVariant())->setSize('M');
        $prod1->addVariant($variant1_s);
        $prod1->addVariant($variant1_m);

        $variant2_s = (new ProductVariant())->setSize('S');
        $variant2_m = (new ProductVariant())->setSize('M');
        $prod2->addVariant($variant2_s);
        $prod2->addVariant($variant2_m);

        $manager->persist($variant1_s);
        $manager->persist($variant1_m);
        $manager->persist($variant2_s);
        $manager->persist($variant2_m);

        // Stock pour variantes
        $stock1 = (new Stock())
            ->setVariant($variant1_s)
            ->setLocation($mag1)
            ->setQuantity(20);
        $manager->persist($stock1);

        $stock2 = (new Stock())
            ->setVariant($variant1_m)
            ->setLocation($mag1)
            ->setQuantity(5);
        $manager->persist($stock2);

        $stock3 = (new Stock())
            ->setVariant($variant2_s)
            ->setLocation($mag2)
            ->setQuantity(0);
        $manager->persist($stock3);

        $stock4 = (new Stock())
            ->setVariant($variant2_m)
            ->setLocation($mag2)
            ->setQuantity(3);
        $manager->persist($stock4);

        // Utilisateurs
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass'));
        $manager->persist($admin);

        $vendeuse = new User();
        $vendeuse->setUsername('vendeuse');
        $vendeuse->setRoles(['ROLE_USER']);
        $vendeuse->setPassword($this->passwordHasher->hashPassword($vendeuse, 'userpass'));
        $manager->persist($vendeuse);

        $manager->flush();
    }
}
