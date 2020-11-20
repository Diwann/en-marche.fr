<?php

namespace App\Repository;

use App\Entity\BaseEventCategory;
use App\Entity\EventGroupCategory;
use Doctrine\Common\Persistence\ManagerRegistry;

class EventGroupCategoryRepository extends BaseEventCategoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventGroupCategory::class);
    }

    public function findAllEnabledOrderedByName(): array
    {
        return $this
            ->createQueryBuilder('egc')
            ->addSelect('ec')
            ->join('egc.eventCategories', 'ec')
            ->where('egc.status = :status')
            ->andWhere('ec.status = :status')
            ->setParameter('status', BaseEventCategory::ENABLED)
            ->orderBy('egc.name', 'ASC')
            ->addOrderBy('ec.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
