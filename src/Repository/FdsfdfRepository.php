<?php

namespace App\Repository;

use App\Entity\Fdsfdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fdsfdf>
 *
 * @method Fdsfdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fdsfdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fdsfdf[]    findAll()
 * @method Fdsfdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FdsfdfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fdsfdf::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Fdsfdf $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Fdsfdf $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Fdsfdf[] Returns an array of Fdsfdf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fdsfdf
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
