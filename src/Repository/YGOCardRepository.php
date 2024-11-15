<?php

namespace App\Repository;

use App\Entity\YGOCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<YGOCard>
 */
class YGOCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YGOCard::class);
    }
    
    /**
     * @return YGOCard[] Returns an array of [Objet] objects for a member
     */
    public function findUserYGOCards(User $user): array
    {
        return $this->createQueryBuilder('o')
        ->leftJoin('o.pack', 'p')
        ->andWhere('p.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult()
        ;
    }
    

    //    /**
    //     * @return YGOCard[] Returns an array of YGOCard objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('y')
    //            ->andWhere('y.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('y.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?YGOCard
    //    {
    //        return $this->createQueryBuilder('y')
    //            ->andWhere('y.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
