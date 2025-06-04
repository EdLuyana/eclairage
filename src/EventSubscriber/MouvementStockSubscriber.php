<?php

namespace App\EventSubscriber;

use App\Entity\MouvementStock;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: 'postPersist', entity: MouvementStock::class)]
class MouvementStockSubscriber
{
public function __construct(private EntityManagerInterface $em) {}

public function postPersist(MouvementStock $mouvement, PostPersistEventArgs $event): void
{
$product = $mouvement->getProduct();
$emplacement = $mouvement->getEmplacement();

// Chercher le stock correspondant
$stockRepo = $this->em->getRepository(Stock::class);
$stock = $stockRepo->findOneBy([
'product' => $product,
'emplacement' => $emplacement,
]);

if (!$stock) {
// S’il n’existe pas, on le crée
$stock = new Stock();
$stock->setProduct($product);
$stock->setEmplacement($emplacement);
$stock->setQuantity(0);
}

// Mise à jour de la quantité
$newQuantity = $stock->getQuantity() + $mouvement->getQuantity();
$stock->setQuantity($newQuantity);
$stock->setUpdatedAt(new \DateTimeImmutable());

$this->em->persist($stock);
$this->em->flush();
}
}
