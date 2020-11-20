<?php

namespace App\VotingPlatform\Designation;

use MyCLabs\Enum\Enum;

final class DesignationTypeEnum extends Enum
{
    public const COMMITTEE_ADHERENT = 'committee_adherent';
    public const COMMITTEE_SUPERVISOR = 'committee_supervisor';
    public const COPOL = 'copol';
}
