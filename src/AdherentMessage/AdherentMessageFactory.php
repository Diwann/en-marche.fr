<?php

namespace App\AdherentMessage;

use App\Entity\Adherent;
use App\Entity\AdherentMessage\AdherentMessageInterface;
use Ramsey\Uuid\Uuid;

class AdherentMessageFactory
{
    public static function create(
        Adherent $adherent,
        AdherentMessageDataObject $dataObject,
        string $type
    ): AdherentMessageInterface {
        if (!isset(AdherentMessageTypeEnum::CLASSES[$type])) {
            throw new \InvalidArgumentException(sprintf('Message type "%s" is undefined', $type));
        }

        $className = AdherentMessageTypeEnum::CLASSES[$type];

        return (new $className(Uuid::uuid4(), $adherent))->updateFromDataObject($dataObject);
    }
}
