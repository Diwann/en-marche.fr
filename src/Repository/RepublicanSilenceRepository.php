<?php

namespace App\Repository;

use App\Entity\RepublicanSilence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RepublicanSilenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepublicanSilence::class);
    }

    /**
     * @return RepublicanSilence[]
     */
    public function findStarted(\DateTimeInterface $startDate): iterable
    {
        return $this
            ->createQueryBuilder('silence')
            ->where('silence.beginAt <= :date AND silence.finishAt > :date')
            ->setParameter('date', $startDate)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return RepublicanSilence[]
     */
    public function findFromDate(\DateTimeInterface $date): iterable
    {
        return $this
            ->createQueryBuilder('silence')
            ->where('silence.finishAt >= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }
}
