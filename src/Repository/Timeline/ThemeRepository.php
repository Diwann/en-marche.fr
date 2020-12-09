<?php

namespace App\Repository\Timeline;

use App\Entity\Timeline\Theme;
use App\Repository\TranslatableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ThemeRepository extends ServiceEntityRepository
{
    use TranslatableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

    public function findOneByTitle(string $title): ?Theme
    {
        return $this
            ->createQueryBuilder('theme')
            ->join('theme.translations', 'translations')
            ->andWhere('translations.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
