<?php

namespace App\Normalizer;

use App\Adherent\AdherentRoleEnum;
use App\Entity\IdeasWorkshop\AuthorCategoryEnum;
use App\Entity\IdeasWorkshop\Idea;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class IdeaDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'IDEA_DENORMALIZER_ALREADY_CALLED';

    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $context[self::ALREADY_CALLED] = true;

        /** @var Idea $data */
        $data = $this->denormalizer->denormalize($data, $class, $format, $context);

        if ($this->authorizationChecker->isGranted(AdherentRoleEnum::LAREM)) {
            $data->setAuthorCategory(AuthorCategoryEnum::QG);
        } elseif ($this->authorizationChecker->isGranted(AdherentRoleEnum::ELECTED)) {
            $data->setAuthorCategory(AuthorCategoryEnum::ELECTED);
        } elseif ($data->getCommittee()) {
            $data->setAuthorCategory(AuthorCategoryEnum::COMMITTEE);
        } else {
            $data->setAuthorCategory(AuthorCategoryEnum::ADHERENT);
        }

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return !isset($context[self::ALREADY_CALLED]) && Idea::class === $type;
    }
}
