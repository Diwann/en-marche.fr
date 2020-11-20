<?php

namespace App\Repository;

use App\Entity\EventCategory;
use App\Entity\EventGroupCategory;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class EventCategoryRepository extends BaseEventCategoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventCategory::class);
    }

    public function createQueryForAllEnabledOrderedByName(?EventGroupCategory $groupCategory): QueryBuilder
    {
        $qb = $this->createQueryBuilder('ec')
                ->join('ec.eventGroupCategory', 'egc')
                ->where('ec.status = :status')
                ->andWhere('egc.status = :status')
                ->orderBy('ec.eventGroupCategory', 'ASC')
                ->addOrderBy('ec.name', 'ASC')
                ->setParameters([
                    'status' => EventCategory::ENABLED,
                ])
        ;

        if ($groupCategory) {
            $qb
                ->andWhere('ec.eventGroupCategory = :groupCategory')
                ->setParameter('groupCategory', $groupCategory)
            ;
        }

        return $qb;
    }
}
