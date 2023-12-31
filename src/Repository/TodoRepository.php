<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Todo>
 *
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }

    public function save(Todo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Todo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Todo[] Returns an array of Todo objects
     */
    public function findFullTodo($orderby, $order): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.category', 'category')
            ->orderBy('t.'.$orderby, $order)
            ->getQuery()
            ->getResult();
    }


    public function search($searchTerms, $criteria = null):array
    {
        $querybuilder = $this->createQueryBuilder('t')
            ->leftJoin('t.category', 'category');

        if($searchTerms){
            $querybuilder
                ->where('t.name LIKE :val')
                ->setParameter('val',  '%'.$searchTerms.'%') ;
        }

        if($criteria){
            $querybuilder
                ->andWhere('t.done = :isDone')
                ->setParameter('isDone', $criteria['done']);
        }

        return $querybuilder
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Todo[] Returns an array of Todo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Todo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
