<?php

namespace App\Repository\Newsletters;

use App\Entity\Newsletters\Categoriesn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categoriesn>
 *
 * @method Categoriesn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoriesn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoriesn[]    findAll()
 * @method Categoriesn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoriesn::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Categoriesn $entity, bool $flush = true): void
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
    public function remove(Categoriesn $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Categoriesn[] Returns an array of Categoriesn objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categoriesn
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
