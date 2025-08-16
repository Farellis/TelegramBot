<?php
namespace App\Repository;

use App\Entity\Trade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trade>
 *
 * @method Trade|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trade|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trade[]    findAll()
 * @method Trade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trade::class);
    }

    /**
     * Get the sum of fees_app from all Trade objects.
     *
     * @return float|int
     */
    public function getSumOfFeesApp(string $network)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('SUM(t.fees_app) as sumFeesApp')
            ->join('t.position', 'p')
            ->join('p.token', 'tok')
            ->where('tok.network = :network')
            ->andWhere('t.tx_result = 1')
            ->setParameter('network', $network)
            ->getQuery();

        $result = $qb->getSingleScalarResult();

        return $result ?: 0; // Retourne 0 si null
    }

    /**
     * Get the volume from all Trade objects.
     *
     * @return float|int
     */
    public function getVolumeNative(string $network)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('SUM(ABS(t.quantity_native)) as volumeNative')
            ->join('t.position', 'p')
            ->join('p.token', 'tok')
            ->where('tok.network = :network')
            ->andWhere('t.tx_result = 1')
            ->setParameter('network', $network)
            ->getQuery();

        $result = $qb->getSingleScalarResult();

        return $result ?: 0; // Retourne 0 si null
    }
//    /**
//     * @return Trade[] Returns an array of Trade objects
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
//    public function findOneBySomeField($value): ?Trade
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
