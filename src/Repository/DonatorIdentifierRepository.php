<?php

namespace App\Repository;

use App\Entity\DonatorIdentifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DonatorIdentifierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DonatorIdentifier::class);
    }

    public function findLastIdentifier(): ?DonatorIdentifier
    {
        return $this
            ->createQueryBuilder('donator')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
