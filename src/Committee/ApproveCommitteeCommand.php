<?php

namespace App\Committee;

use App\Address\Address;
use App\Entity\Committee;
use App\ValueObject\Genders;

class ApproveCommitteeCommand extends CommitteeCommand
{
    protected $nameLocked = false;
    /**
     * @@Assert\NotBlank
     */
    protected $slug;

    public static function constructFromCommittee(Committee $committee): self
    {
        $dto = new self(Address::createFromAddress($committee->getPostAddress()));
        $dto->name = $committee->getName();
        $dto->description = $committee->getDescription();
        $dto->phone = $committee->getPhone();
        $dto->facebookPageUrl = $committee->getFacebookPageUrl();
        $dto->twitterNickname = $committee->getTwitterNickname();
        $dto->committee = $committee;
        $dto->nameLocked = $committee->isNameLocked();
        $dto->slug = $committee->getSlug();
        $provisionalSupervisorF = $committee->getProvisionalSupervisorByGender(Genders::FEMALE);
        $provisionalSupervisorM = $committee->getProvisionalSupervisorByGender(Genders::MALE);
        $dto->provisionalSupervisorFemale = $provisionalSupervisorF ? $provisionalSupervisorF->getAdherent() : null;
        $dto->provisionalSupervisorMale = $provisionalSupervisorM ? $provisionalSupervisorM->getAdherent() : null;

        return $dto;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isNameLocked(): bool
    {
        return $this->nameLocked;
    }

    public function setNameLocked(bool $nameLocked): void
    {
        $this->nameLocked = $nameLocked;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
