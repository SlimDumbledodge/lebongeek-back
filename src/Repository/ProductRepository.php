<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findBySearch($query): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.ad', 'a')
            ->join('p.category', 'c')
            ->join('p.user', 'u')
            ->where('p.title LIKE :q')
            ->orWhere('a.title LIKE :q')
            ->orWhere('a.description LIKE :q')
            ->orWhere('c.name LIKE :q')
            ->orWhere('u.username LIKE :q')
            ->setParameter('q', "%$query%")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findProductByAdId($adId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.ad', 'a')
            ->where('a = :adId')
            ->setParameter('adId', $adId)
            ->getQuery()
            ->getResult();
    }
}
