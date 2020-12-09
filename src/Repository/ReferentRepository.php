<?php

namespace App\Repository;

use App\Entity\Referent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ReferentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referent::class);
    }

    public function findByStatusOrderedByAreaLabel(string $status = Referent::ENABLED): array
    {
        return $this->createQueryBuilder('referent')
            ->where('referent.status = :status')
            ->setParameter('status', $status)
            ->orderBy('referent.areaLabel')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByEmailAndSelectPersonOrgaChart(string $email): Referent
    {
        return $this->createQueryBuilderWithEmail($email)
            ->addSelect('referent_person_links')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findOneByEmail(string $email): ?Referent
    {
        return $this->createQueryBuilderWithEmail($email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function exists(string $email): bool
    {
        return 0 < $this->createQueryBuilderWithEmail($email)
            ->select('COUNT(1)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    private function createQueryBuilderWithEmail(string $email): QueryBuilder
    {
        return $this->createQueryBuilder('referent')
            ->leftJoin('referent.referentPersonLinks', 'referent_person_links')
            ->where('referent.emailAddress = :email')
            ->setParameter('email', $email)
        ;
    }
}
