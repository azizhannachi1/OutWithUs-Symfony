<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reclamation $entity, bool $flush = true): void
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
    public function remove(Reclamation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reclamation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function OrderByMailDQL(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('
           select s from App\Entity\Reclamation s order by s.email ASC');
           return $query->getResult();
    }

    public function OrderByMailQB(){
        return $this->createQueryBuilder('s')->orderBy('s.email','ASC')
        ->getQuery()->getResult();
    }

    /**
     * Returns number of "Reclamations" per day
     * @return void 
     */
    public function countByDate(){
        // $query = $this->createQueryBuilder('a')
        //     ->select('SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count')
        //     ->groupBy('dateAnnonces')
        // ;
        // return $query->getQuery()->getResult();
        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.date, 1, 10) as date, COUNT(a) as count FROM App\Entity\Reclamation a GROUP BY date
        ");
        return $query->getResult();
    }



    /**
     * Returns Reclamation between 2 dates
     */
    public function selectInterval($from, $to, $cat = null){
      
        $query = $this->createQueryBuilder('a')
            ->where('a.date > :from')
            ->andWhere('a.date < :to')
            ->setParameter(':from', $from)
            ->setParameter(':to', $to);
        if($cat != null){
            $query->leftJoin('a.sujet', 'c')
                ->andWhere('c.id = :cat')
                ->setParameter(':cat', $cat);
        }
        return $query->getQuery()->getResult();
    }

    public function getPaginatedReclamations($page, $limit){
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.date')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ;

        
        return $query->getQuery()->getResult();
    }

    /**
     * Returns number of Annonces
     * @return void 
     */
    public function getTotalReclamations(){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ;
        
        return $query->getQuery()->getResult();
    }

}
