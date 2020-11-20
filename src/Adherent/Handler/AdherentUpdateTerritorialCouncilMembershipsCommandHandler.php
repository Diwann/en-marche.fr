<?php

namespace App\Adherent\Handler;

use App\Repository\AdherentRepository;
use App\TerritorialCouncil\Command\AdherentUpdateTerritorialCouncilMembershipsCommand;
use App\TerritorialCouncil\Handlers\AbstractTerritorialCouncilHandler;
use App\TerritorialCouncil\Handlers\TerritorialCouncilMembershipHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AdherentUpdateTerritorialCouncilMembershipsCommandHandler implements MessageHandlerInterface
{
    /**
     * @var TerritorialCouncilMembershipHandlerInterface[]|iterable
     */
    private $handlers;
    private $adherentRepository;
    private $entityManager;

    public function __construct(
        AdherentRepository $adherentRepository,
        EntityManagerInterface $entityManager,
        iterable $handlers
    ) {
        $this->adherentRepository = $adherentRepository;
        $this->entityManager = $entityManager;
        $this->handlers = $handlers;
    }

    public function __invoke(AdherentUpdateTerritorialCouncilMembershipsCommand $command): void
    {
        $adherent = $this->adherentRepository->findByUuid($command->getUuid()->toString());

        if (!$adherent) {
            return;
        }

        $this->entityManager->refresh($adherent);

        foreach ($this->getHandlers() as $handler) {
            if ($handler->supports($adherent)) {
                if (!$command->isEventDispatchingEnabled()) {
                    $handler->disableEventDispatching();
                }

                $handler->handle($adherent);
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @return TerritorialCouncilMembershipHandlerInterface[]
     */
    private function getHandlers(): array
    {
        $handlers = iterator_to_array($this->handlers);

        usort($handlers, function (AbstractTerritorialCouncilHandler $handlerA, AbstractTerritorialCouncilHandler $handlerB) {
            return $handlerA->getPriority() <=> $handlerB->getPriority();
        });

        return $handlers;
    }
}
