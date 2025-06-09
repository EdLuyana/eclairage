<?php

namespace App\Repository;

use App\Entity\ProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductVariant>
 *
 * @method ProductVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductVariant[]    findAll()
 * @method ProductVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductVariant::class);
    }

    /**
     * Recherche une variante de produit par référence produit et taille.
     */
    public function findOneByReferenceAndSize(string $reference, string $size): ?ProductVariant
    {
        return $this->createQueryBuilder('v')
            ->join('v.product', 'p')
            ->where('p.reference = :ref')
            ->andWhere('v.size = :size')
            ->setParameters([
                'ref' => $reference,
                'size' => $size
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
