<?php

namespace App\Repository;

use App\Entity\DepositWallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepositWallet>
 *
 * @method DepositWallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepositWallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepositWallet[]    findAll()
 * @method DepositWallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepositWalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepositWallet::class);
    }

//    /**
//     * @return DepositWallet[] Returns an array of DepositWallet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DepositWallet
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
