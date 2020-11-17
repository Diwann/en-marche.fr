<?php

namespace App\Controller\EnMarche\ManagedUsers;

use App\Form\ManagedUsers\CandidateManagedUsersFilterType;
use App\ManagedUsers\ManagedUsersFilter;
use App\Subscription\SubscriptionTypeEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/espace-candidat", name="app_candidate_managed_users_", methods={"GET"})
 *
 * @Security("is_granted('ROLE_CANDIDATE')")
 */
class CandidateManagedUsersController extends AbstractManagedUsersController
{
    public const SPACE_NAME = 'candidate';

    protected function getSpaceType(): string
    {
        return self::SPACE_NAME;
    }

    protected function createFilterForm(ManagedUsersFilter $filter = null): FormInterface
    {
        return $this->createForm(CandidateManagedUsersFilterType::class, $filter, [
            'method' => Request::METHOD_GET,
            'csrf_protection' => false,
            'space_type' => $this->getSpaceType(),
        ]);
    }

    protected function createFilterModel(Request $request): ManagedUsersFilter
    {
        $session = $request->getSession();

        return new ManagedUsersFilter(
            SubscriptionTypeEnum::CANDIDATE_EMAIL,
            [$this->getMainUser($session)->getCandidateManagedArea()->getZone()],
            [],
            [],
            [],
        );
    }
}
