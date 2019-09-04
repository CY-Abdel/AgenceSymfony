<?php

namespace App\Repository;


use App\Entity\Maisons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * @method Maisons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maisons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maisons[]    findAll()
 * @method Maisons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaisonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maisons::class);
    }

    // le ': array' c'est pour le typage mais il renvoi ici un tabl de Maisons
    /**
     * @return Query
     */
    public function findAllVisibleQuery()
    {
        // return $this->createQueryBuilder('p')
        // ->andWhere('p.sold = false')
        return $this->findVisibleQuery()
            ->getQuery()
            // ->getResult()
        ;
    }

    /**
     * @return Maisons
     */
    public function findLatest() : array
    {
        // return $this->createQueryBuilder('p')
        //     ->where('p.sold = false')
        return $this->findVisibleQuery()
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * utiliser cette function pour eviter de repeter les memes ecritures chaque fois
     */
     private function findVisibleQuery()
     {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sold = false')
        ;
     }

    // /**
    //  * @return Maisons[] Returns an array of Maisons objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Maisons
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
