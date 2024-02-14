<?php

namespace App\Repository;

use App\Entity\Ads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ads>
 *
 * @method Ads|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ads|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ads[]    findAll()
 * @method Ads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ads::class);
    }
    public function findByDepartment($departmentNumber)
    {
        return $this->createQueryBuilder('a')
            ->join('a.city', 'c')
            ->andWhere('c.department_number = :departmentNumber')
            ->setParameter('departmentNumber', $departmentNumber)
            ->getQuery()
            ->getResult();
    }
    //Cherche les ads selon filtres demandés
    public function findByFilters($departmentNumber, $type, $status, $transaction, $minPrice, $maxPrice)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        //Filtre par departement si un filtre est appliqué
        if($departmentNumber){
            $queryBuilder
            ->join('a.city', 'c')
            ->andWhere('c.department_number = :departmentNumber')
            ->setParameter('departmentNumber', $departmentNumber);
        }
        //Filtre par type si un filtre est appliqué
        if ($type) {
            $queryBuilder
                ->join('a.Type', 't')
                ->andWhere('t.id = :type')
                ->setParameter('type', $type);
        }
         //Filtre par dstatus si un filtre est appliqué
        if ($status) {
            $queryBuilder
            ->join ('a.Status', 's')
            ->andWhere ('s.id = :status')
            ->setParameter('status', $status);
        }
        if ($transaction) {
            $queryBuilder
            -> join ('a.Transaction', 'tr')
            ->andWhere('tr.id = :transaction')
            ->setParameter('transaction', $transaction);
        }
        
        if ($minPrice) {
            $queryBuilder
                ->andWhere('a.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice) {
            $queryBuilder
                ->andWhere('a.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }
    
        return $queryBuilder->getQuery()->getResult();
    }
    
    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Ads[] Returns an array of Ads objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ads
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
