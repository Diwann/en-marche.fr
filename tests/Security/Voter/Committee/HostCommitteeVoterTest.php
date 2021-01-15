<?php

namespace Tests\App\Security\Voter\Committee;

use App\Committee\CommitteePermissions;
use App\Entity\Adherent;
use App\Entity\Committee;
use App\Security\Voter\AbstractAdherentVoter;
use App\Security\Voter\Committee\HostCommitteeVoter;
use Tests\App\Security\Voter\AbstractAdherentVoterTest;

class HostCommitteeVoterTest extends AbstractAdherentVoterTest
{
    public function provideAnonymousCases(): iterable
    {
        yield [false, true, CommitteePermissions::HOST, $this->createMock(Committee::class)];
    }

    protected function getVoter(): AbstractAdherentVoter
    {
        return new HostCommitteeVoter();
    }

    public function testAdherentCannotHostNotApprovedCommittee()
    {
        $committee = $this->getCommitteeMock(false);
        $adherent = $this->getAdherentMock(false, $committee);

        // Hosts cannot either
        $adherent->expects($this->never())
            ->method('isHostOf')
        ;

        $this->assertGrantedForAdherent(false, true, $adherent, CommitteePermissions::HOST, $committee);
    }

    public function testAdherentCanHostNotApprovedCommitteeIfSupervisor()
    {
        $committee = $this->getCommitteeMock(false);
        $adherent = $this->getAdherentMock(true, $committee);

        $this->assertGrantedForAdherent(true, true, $adherent, CommitteePermissions::HOST, $committee);
    }

    public function testAdherentCannotHostNotApprovedCommitteeIfCreator()
    {
        $committee = $this->getCommitteeMock(false);
        $adherent = $this->getAdherentMock(false, $committee, true);

        $this->assertGrantedForAdherent(false, true, $adherent, CommitteePermissions::HOST, $committee);
    }

    public function testAdherentCannotHostApprovedCommittee()
    {
        $committee = $this->getCommitteeMock(true);
        $adherent = $this->getAdherentMock(null, $committee, false, false);

        $this->assertGrantedForAdherent(false, true, $adherent, CommitteePermissions::HOST, $committee);
    }

    public function testHostIsGrantedForApprovedCommittee()
    {
        $committee = $this->getCommitteeMock(true);
        $adherent = $this->getAdherentMock(null, $committee, false, true);

        $this->assertGrantedForAdherent(true, true, $adherent, CommitteePermissions::HOST, $committee);
    }

    /**
     * @param bool                                                    $isSupervisor
     * @param Committee|\PHPUnit_Framework_MockObject_MockObject|null $committee
     * @param bool|null                                               $isCreator
     *
     * @return Adherent|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAdherentMock(
        bool $isSupervisor = null,
        Committee $committee = null,
        bool $isCreator = false,
        bool $isHost = null
    ): Adherent {
        $adherent = $this->createAdherentMock();

        if (null !== $isSupervisor) {
            $adherent->expects($this->once())
                ->method('isSupervisorOf')
                ->with($committee)
                ->willReturn($isSupervisor)
            ;
        } else {
            $adherent->expects($this->never())
                ->method('isSupervisorOf')
            ;
        }

        if (null !== $isHost) {
            $adherent->expects($this->once())
                ->method('isHostOf')
                ->with($committee)
                ->willReturn($isHost)
            ;
        }

        return $adherent;
    }

    /**
     * @return Committee|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getCommitteeMock(bool $approved): Committee
    {
        $committee = $this->createMock(Committee::class);

        $committee->expects($this->once())
            ->method('isApproved')
            ->willReturn($approved)
        ;

        return $committee;
    }
}
