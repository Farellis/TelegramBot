<?php

namespace App\Repository;

use App\Entity\Buffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Buffer>
 *
 * @method Buffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Buffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Buffer[]    findAll()
 * @method Buffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BufferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Buffer::class);
    }

//    /**
//     * @return Buffer[] Returns an array of Buffer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Buffer
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
