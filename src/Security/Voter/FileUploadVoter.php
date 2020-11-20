<?php

namespace App\Security\Voter;

use App\Documents\DocumentPermissions;
use App\Entity\Adherent;
use App\Entity\UserDocument;

class FileUploadVoter extends AbstractAdherentVoter
{
    protected function supports($attribute, $type)
    {
        return DocumentPermissions::FILE_UPLOAD === $attribute;
    }

    /**
     * @param string $type
     */
    protected function doVoteOnAttribute(string $attribute, Adherent $adherent, $type): bool
    {
        switch ($type) {
            case UserDocument::TYPE_REFERENT:
                return $adherent->isReferent();
            case UserDocument::TYPE_COMMITTEE_CONTACT:
            case UserDocument::TYPE_COMMITTEE_FEED:
                return $adherent->isHost();
            case UserDocument::TYPE_EVENT:
                return $adherent->isReferent() || $adherent->isHost();
            case UserDocument::TYPE_ADHERENT_MESSAGE:
                return $adherent->isAdherentMessageRedactor();
            case UserDocument::TYPE_TERRITORIAL_COUNCIL_FEED:
                return $adherent->isTerritorialCouncilPresident();
            default:
                return false;
        }
    }
}
