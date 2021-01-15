<?php

namespace App\Committee\EventListener;

use App\Committee\CommitteeAdherentMandateManager;
use App\Committee\CommitteeEvent;
use App\Committee\CommitteeManager;
use App\Events;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApproveCommitteeListener implements EventSubscriberInterface
{
    private $committeeManager;
    private $mandateManager;
    private $entityManager;

    public function __construct(
        CommitteeManager $committeeManager,
        CommitteeAdherentMandateManager $mandateManager,
        EntityManagerInterface $entityManager
    ) {
        $this->committeeManager = $committeeManager;
        $this->mandateManager = $mandateManager;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::COMMITTEE_APPROVED => 'updateSupervisorProvisionalMandates',
        ];
    }

    public function updateSupervisorProvisionalMandates(CommitteeEvent $event): void
    {
        $committee = $event->getCommittee();
        foreach ($committee->getProvisionalSupervisors() as $provisionalSupervisor) {
            $this->mandateManager->updateSupervisorProvisionalMandate($provisionalSupervisor->getAdherent(), $committee);
            $this->committeeManager->followCommittee($provisionalSupervisor->getAdherent(), $committee, true);
        }
    }
}
