<?php

namespace AppBundle\Entity\Biography;

use Algolia\AlgoliaSearchBundle\Mapping\Annotation as Algolia;
use AppBundle\Validator\UniqueExecutiveOfficer;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Biography\ExecutiveOfficeMemberRepository")
 * @ORM\Table(
 *     name="biography_executive_office_member",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="executive_office_member_uuid_unique", columns="uuid"),
 *         @ORM\UniqueConstraint(name="executive_office_member_slug_unique", columns="slug")
 *     }
 * )
 *
 * @UniqueExecutiveOfficer
 *
 * @Algolia\Index(autoIndex=false)
 */
class ExecutiveOfficeMember extends AbstractBiography
{
    /**
     * @ORM\Column
     *
     * @Assert\Length(max=255)
     */
    private $job;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $executiveOfficer = false;

    public function __construct(
        UuidInterface $uuid = null,
        string $firstName = null,
        string $lastName = null,
        string $description = null,
        string $content = null,
        bool $published = null,
        string $job = null,
        bool $executiveOfficer = null
    ) {
        parent::__construct($uuid, $firstName, $lastName, $description, $content, $published);

        $this->job = $job;
        $this->executiveOfficer = $executiveOfficer;
    }

    public function setExecutiveOfficer(bool $executiveOfficer): void
    {
        $this->executiveOfficer = $executiveOfficer;
    }

    public function isExecutiveOfficer(): ?bool
    {
        return $this->executiveOfficer;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): void
    {
        $this->job = $job;
    }

    public function getImagePath(): string
    {
        return sprintf('images/biographies/notre-organisation/%s', $this->getImageName());
    }
}
