<?php

namespace App\Repository\ApplicationRequest;

use App\Entity\ApplicationRequest\TechnicalSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class TechnicalSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechnicalSkill::class);
    }

    public function createDisplayabledQueryBuilder(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('t')
            ->andWhere('t.display = 1')
            ->orderBy('t.name', 'ASC')
        ;
    }
}
