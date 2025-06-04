<?php

namespace App\EventSubscriber;

use App\Entity\StockMovement;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: 'postPersist', entity: StockMovement::class)]
class StockMovementSubscriber
{
public function __construct(private EntityManagerInterface $em) {}

public function postPersist(StockMovement $movement, PostPersistEventArgs $event): void
{
$product = $movement->getProduct();
$location = $movement->getLocation();

// Chercher le stock correspondant
$stockRepo = $this->em->getRepository(Stock::class);
$stock = $stockRepo->findOneBy([
'product' => $product,
'location' => $location,
]);

if (!$stock) {
// S’il n’existe pas, on le crée
$stock = new Stock();
$stock->setProduct($product);
$stock->setLocation($location);
$stock->setQuantity(0);
}

// Mise à jour de la quantité
$newQuantity = $stock->getQuantity() + $movement->getQuantity();

if ($newQuantity < 0){
    throw new \LogicException('Stock insuffisant pour effectuer ce movement. Opération annulée.');
}
$stock->setQuantity($newQuantity);
$stock->setUpdatedAt(new \DateTimeImmutable());

$this->em->persist($stock);
$this->em->flush();
}
}
