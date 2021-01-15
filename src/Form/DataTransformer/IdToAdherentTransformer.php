<?php

namespace App\Form\DataTransformer;

use App\Entity\Adherent;
use App\Repository\AdherentRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToAdherentTransformer implements DataTransformerInterface
{
    private $adherentRepository;

    public function __construct(AdherentRepository $adherentRepository)
    {
        $this->adherentRepository = $adherentRepository;
    }

    public function transform($value)
    {
        if ($value instanceof Adherent) {
            return $value->getId();
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        if ($value) {
            if (!$adherent = $this->adherentRepository->findOneByEmail($value)) {
                throw new TransformationFailedException('Adhérent avec cet e-mail n\'existe pas');
            }

            return $adherent;
        }

        return $value;
    }
}
