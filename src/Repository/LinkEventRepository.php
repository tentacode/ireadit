<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LinkEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkEvent>
 *
 * @method LinkEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkEvent[]    findAll()
 * @method LinkEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkEvent::class);
    }

    public function add(LinkEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(LinkEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

//    /**
//     * @return LinkEvent[] Returns an array of LinkEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LinkEvent
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
