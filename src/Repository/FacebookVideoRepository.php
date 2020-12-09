<?php

namespace App\Repository;

use App\Entity\FacebookVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class FacebookVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookVideo::class);
    }

    /**
     * @return FacebookVideo[]
     */
    public function findPublishedVideos(): array
    {
        return $this->findBy(['published' => true], ['position' => 'ASC']);
    }
}
