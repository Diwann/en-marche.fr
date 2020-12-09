<?php

namespace App\Repository\Election;

use App\Entity\City;
use App\Entity\Election\VoteResultListCollection;
use App\Entity\ElectionRound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class VoteResultListCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteResultListCollection::class);
    }

    public function findOneByCity(City $city, ElectionRound $electionRound): ?VoteResultListCollection
    {
        return $this->createQueryBuilder('lc')
            ->innerJoin('lc.city', 'city')
            ->where('city = :city AND lc.electionRound = :round')
            ->setParameters([
                'city' => $city,
                'round' => $electionRound,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByCityInseeCode(string $inseeCode, ElectionRound $electionRound): ?VoteResultListCollection
    {
        return $this->createQueryBuilder('lc')
            ->innerJoin('lc.city', 'city')
            ->where('city.inseeCode = :insee_code AND lc.electionRound = :round')
            ->setParameters([
                'insee_code' => $inseeCode,
                'round' => $electionRound,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
