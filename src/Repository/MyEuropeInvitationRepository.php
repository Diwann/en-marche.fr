<?php

namespace App\Repository;

use App\Entity\MyEuropeInvitation;
use Doctrine\Common\Persistence\ManagerRegistry;

class MyEuropeInvitationRepository extends InteractiveInvitationRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MyEuropeInvitation::class);
    }
}
