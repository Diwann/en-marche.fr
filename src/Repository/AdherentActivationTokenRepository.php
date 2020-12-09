<?php

namespace App\Repository;

use App\Entity\AdherentActivationToken;
use Doctrine\Common\Persistence\ManagerRegistry;

class AdherentActivationTokenRepository extends AbstractAdherentTokenRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdherentActivationToken::class);
    }
}
