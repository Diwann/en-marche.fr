<?php

namespace App\AdherentMessage\Filter;

use App\AdherentMessage\AdherentMessageTypeEnum;
use App\Entity\Adherent;
use App\Entity\AdherentMessage\Filter\LreManagerElectedRepresentativeFilter;
use App\Entity\UserListDefinitionEnum;
use App\Exception\InvalidAdherentMessageType;
use App\Form\AdherentMessage\AdherentGeoZoneFilterType;
use App\Form\AdherentMessage\AdherentZoneFilterType;
use App\Form\AdherentMessage\CommitteeFilterType;
use App\Form\AdherentMessage\ElectedRepresentativeFilterType;
use App\Form\AdherentMessage\MunicipalChiefFilterType;
use App\Form\AdherentMessage\ReferentElectedRepresentativeFilterType;
use App\Form\AdherentMessage\ReferentFilterType;
use App\Form\AdherentMessage\ReferentInstancesFilterType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FilterFormFactory
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function createForm(string $messageType, $data, Adherent $adherent): FormInterface
    {
        switch ($messageType) {
            case AdherentMessageTypeEnum::REFERENT:
                return $this->formFactory->create(
                    ReferentFilterType::class,
                    $data,
                    [
                        'single_zone' => 1 === \count($managedArea = $adherent->getManagedAreaTagCodes()),
                        'is_referent_from_paris' => (bool) array_filter(
                            $managedArea,
                            function ($code) { return 0 === strpos($code, '75'); }
                        ),
                        'referent_tags' => $adherent->getManagedArea()->getTags()->toArray(),
                    ]
                );

            case AdherentMessageTypeEnum::DEPUTY:
                return $this->formFactory->create(AdherentZoneFilterType::class, $data, [
                    'referent_tags' => [$adherent->getManagedDistrict()->getReferentTag()],
                ]);

            case AdherentMessageTypeEnum::SENATOR:
                return $this->formFactory->create(AdherentZoneFilterType::class, $data, [
                    'referent_tags' => [$adherent->getSenatorArea()->getDepartmentTag()],
                ]);

            case AdherentMessageTypeEnum::MUNICIPAL_CHIEF:
                return $this->formFactory->create(MunicipalChiefFilterType::class, $data);

            case AdherentMessageTypeEnum::COMMITTEE:
                return $this->formFactory->create(CommitteeFilterType::class, $data);

            case AdherentMessageTypeEnum::REFERENT_ELECTED_REPRESENTATIVE:
                return $this->formFactory->create(ReferentElectedRepresentativeFilterType::class, $data);

            case AdherentMessageTypeEnum::LRE_MANAGER_ELECTED_REPRESENTATIVE:
                return $this->formFactory->create(
                    ElectedRepresentativeFilterType::class,
                    $data,
                    [
                        'data_class' => LreManagerElectedRepresentativeFilter::class,
                        'user_list_types' => [UserListDefinitionEnum::TYPE_ELECTED_REPRESENTATIVE, UserListDefinitionEnum::TYPE_LRE],
                    ]
                );

            case AdherentMessageTypeEnum::REFERENT_INSTANCES:
                return $this->formFactory->create(ReferentInstancesFilterType::class, $data);

            case AdherentMessageTypeEnum::LEGISLATIVE_CANDIDATE:
                return $this->formFactory->create(AdherentZoneFilterType::class, $data, [
                    'referent_tags' => [$adherent->getLegislativeCandidateManagedDistrict()->getReferentTag()],
                ]);
            case AdherentMessageTypeEnum::CANDIDATE:
                return $this->formFactory->create(AdherentGeoZoneFilterType::class, $data, [
                    'space_type' => AdherentMessageTypeEnum::CANDIDATE,
                ]);
        }

        throw new InvalidAdherentMessageType(sprintf('Invalid message ("%s") type or data', $messageType));
    }
}
