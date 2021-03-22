<?php

namespace App\Repository;

use App\Entity\Transactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;

/**
 * @method Transactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transactions[]    findAll()
 * @method Transactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionsRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 4;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transactions::class);
    }

    public function finByCommssionDepot(int $id, int $page=1)
    {

        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder('t')
            ->innerJoin('t.compte_depot','c')
            ->innerJoin('c.agence','a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $id);
        $query = $queryBuilder->getQuery()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE)
        ;
        $doctrinePaginator = new DoctrinePaginator($query);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }


    public function findByCommissionRetrait(int $id, int $page=1)
    {
        $firstResults = ($page -1) * self::ITEMS_PER_PAGE;

        $queryBuild = $this->createQueryBuilder('t')
            ->innerJoin('t.compte_retrait','c')
            ->innerJoin('c.agence','a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $id);
        $query = $queryBuild->getQuery()
            ->setFirstResult($firstResults)
            ->setMaxResults(self::ITEMS_PER_PAGE)
        ;
        $doctrinePaginator = new DoctrinePaginator($query);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }



    // List Transaction userDepot
    public function findByTransDepot(int $id, int $page=1)
    {
        $firstResults = ($page -1) * self::ITEMS_PER_PAGE;

        $queryBuilders = $this->createQueryBuilder('t')
            ->innerJoin('t.userDepot','u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id);
            $query = $queryBuilders->getQuery()
                ->setFirstResult($firstResults)
                ->setMaxResults(self::ITEMS_PER_PAGE)
            ;
          $doctrinePaginator = new DoctrinePaginator($query);
          $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }

    // List Transaction userRetrait
    public function findByTransRetrait(int $id, int $page=1)
    {
        $firstResults = ($page -1) * self::ITEMS_PER_PAGE;

        $queryBuilders = $this->createQueryBuilder('t')
            ->innerJoin('t.userRetrait','u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id);
        $query = $queryBuilders->getQuery()
            ->setFirstResult($firstResults)
            ->setMaxResults(self::ITEMS_PER_PAGE)
        ;
        $doctrinePaginator = new DoctrinePaginator($query);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }

  /*  public function findByUsersTransaction(int $id)
   {
       return $this->createQueryBuilder('t')
           ->innerJoin('t.compte_depot','cd')
           ->innerJoin('t.compte_retrait','cr')
           ->innerJoin('cd.agence','a')
           ->innerJoin('cr.agence','ag')
           ->andWhere('a.id = :value')
           ->andWhere('ag.id = :val')
           ->setParameter('value', $id)
           ->setParameter('val', $id )
           ->getQuery()
           ->getResult();
   }
*/
    /*
    public function findOneBySomeField($value): ?Transactions
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
