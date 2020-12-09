<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AdherentExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('member_interest_label', [AdherentRuntime::class, 'getMemberInterestLabel']),
            new TwigFunction('get_user_level_label', [AdherentRuntime::class, 'getUserLevelLabel']),
            new TwigFunction('get_adherent_role_labels', [AdherentRuntime::class, 'getAdherentRoleLabels']),
            new TwigFunction('get_referent_previous_visit_date', [AdherentRuntime::class, 'getReferentPreviousVisitDate']),
            new TwigFunction('get_elected_representative', [AdherentRuntime::class, 'getElectedRepresentative']),
            new TwigFunction('has_active_parliamentary_mandate', [AdherentRuntime::class, 'hasActiveParliamentaryMandate']),
            new TwigFunction('get_session_modal_context', [AdherentRuntime::class, 'getSessionModalContext']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('trans_with_gender', [AdherentRuntime::class, 'getTransWithGender']),
        ];
    }
}
