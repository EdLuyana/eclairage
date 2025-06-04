<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Emplacement;
use App\Entity\Product;
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
$cat1 = new Category();
$cat1->setName('Ampoules');
$manager->persist($cat1);

$cat2 = new Category();
$cat2->setName('Lampes');
$manager->persist($cat2);

// Marques
$brand1 = new Brand();
$brand1->setName('Philips');
$manager->persist($brand1);

$brand2 = new Brand();
$brand2->setName('Osram');
$manager->persist($brand2);

// Emplacements
$mag1 = new Emplacement();
$mag1->setName('Magasin Paris');
$manager->persist($mag1);

$mag2 = new Emplacement();
$mag2->setName('Magasin Lyon');
$manager->persist($mag2);

// Produits
$prod1 = new Product();
$prod1->setName('Ampoule LED 60W');
$prod1->setBrand($brand1);
$prod1->setCategory($cat1);
$manager->persist($prod1);

$prod2 = new Product();
$prod2->setName('Lampe de bureau');
$prod2->setBrand($brand2);
$prod2->setCategory($cat2);
$manager->persist($prod2);

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

// Stock initial
$stock1 = new Stock();
$stock1->setProduct($prod1);
$stock1->setEmplacement($mag1);
$stock1->setQuantity(20);
$stock1->setUpdatedAt(new \DateTimeImmutable());
$manager->persist($stock1);

$stock2 = new Stock();
$stock2->setProduct($prod2);
$stock2->setEmplacement($mag2);
$stock2->setQuantity(10);
$stock2->setUpdatedAt(new \DateTimeImmutable());
$manager->persist($stock2);

$manager->flush();
}
}
