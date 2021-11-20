<?php

namespace App\Repository;

use App\Class\Search;
use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    /**
     * Requête sui permet de récupérer les produits en fonction de la recherche l'utilisateur 
     * @return Products[]
     */
    public function findBySearch(Search $search)
    {
       
        $query = $this->createQueryBuilder('p')
                        ->select('c', 'p')
                        ->join('p.category', 'c');
        
        if (!empty($search->categories)) 
        {
            $query = $query->andWhere('c.id in (:categories)')
                            ->setParameter('categories', $search->categories);
        }
        else if (!empty($search->string)) 
        {
            $query = $query->andWhere('p.name LIKE :name')
                            ->setParameter('name', "%{$search->string}%");
        }
        return $query->getQuery()->getResult();
    }


    // public function findOneBySlug($value): ?Products
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.slug = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
