<?php

namespace App\Repository;


use App\Entity\Maisons;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

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
    // public function findAllVisibleQuery()
    public function findAllVisibleQuery(PropertySearch $search) : Query
    {
        // return $this->createQueryBuilder('p')
        // ->andWhere('p.sold = false')
        // return $this->findVisibleQuery()
        //     ->getQuery()
        //     // ->getResult()

        // on change la syntaxe pour pouvoir faire la recherche sa devient :
        $query = $this->findVisibleQuery();
        //pour fair la recherche on utlise la condition if
        if($search->getMaxPrice()){
            $query = $query
                // ->where('p.price <= :maxprice')
                /*si on utilise le where le 2eme ecrase la valeur du premier donc on aura une
                 * erreur pour regler ca on utilisera andWhere il traite chaque condition a part
                 */
                ->andWhere('p.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        if($search->getMinSurface()){
            $query = $query
                // ->where('p.surface >= :minsurface')
                ->andWhere('p.surface >= :minsurface')
                ->setParameter('minsurface', $search->getMinSurface());
        }

        return $query->getQuery();
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
