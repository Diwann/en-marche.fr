<?php

namespace App\Validator\TerritorialCouncil;

use App\TerritorialCouncil\Designation\UpdateDesignationRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TerritorialCouncilDesignationValidator extends ConstraintValidator
{
    private $meetingMaxStartDate;

    public function __construct(int $meetingMaxStartDate)
    {
        $this->meetingMaxStartDate = $meetingMaxStartDate;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TerritorialCouncilDesignation) {
            throw new UnexpectedTypeException($constraint, TerritorialCouncilDesignation::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof UpdateDesignationRequest) {
            throw new UnexpectedTypeException($value, UpdateDesignationRequest::class);
        }

        if ($value->isMeetingMode()) {
            if (!$value->getAddress()) {
                $this->context
                    ->buildViolation($constraint->messageAddressEmpty)
                    ->atPath('address')
                    ->addViolation()
                ;
            }
        } else {
            if (!$value->getMeetingUrl()) {
                $this->context
                    ->buildViolation($constraint->messageUrlEmpty)
                    ->atPath('meetingUrl')
                    ->addViolation()
                ;
            }
        }

        if ($value->isWithPoll()) {
            if (empty(array_filter($value->getElectionPollChoices()))) {
                $this->context
                    ->buildViolation($constraint->messageElectionPollChoiceInvalid)
                    ->atPath('electionPollChoices')
                    ->addViolation()
                ;

                return;
            }

            foreach ($value->getElectionPollChoices() as $choice) {
                if (!\is_integer($choice) || $choice > 10 || $choice < 0) {
                    $this->context
                        ->buildViolation($constraint->messageElectionPollChoiceInvalid)
                        ->atPath('electionPollChoices')
                        ->addViolation()
                    ;

                    return;
                }
            }

            if (!\in_array(0, $value->getElectionPollChoices(), true)) {
                $this->context
                    ->buildViolation($constraint->messageElectionPollChoiceZeroMissing)
                    ->atPath('electionPollChoices')
                    ->addViolation()
                ;
            }
        }

        $voteStartDate = $value->getVoteStartDate();
        $voteEndDate = $value->getVoteEndDate();
        $meetingStartDate = $value->getMeetingStartDate();
        $meetingEndDate = $value->getMeetingEndDate();

        if (!$voteStartDate || !$voteEndDate || !$meetingStartDate || !$meetingEndDate) {
            return;
        }

        $now = new \DateTimeImmutable();

        if ($voteStartDate < $now->modify('+7 days')) {
            $this->context
                ->buildViolation($constraint->messageVoteStartDateTooClose)
                ->atPath('voteStartDate')
                ->addViolation()
            ;
        }

        if ($voteEndDate < (clone $voteStartDate)->modify('+5 hours') || $voteEndDate > (clone $voteStartDate)->modify('+7 days')) {
            $this->context
                ->buildViolation($constraint->messageVoteEndDateInvalid)
                ->atPath('voteEndDate')
                ->addViolation()
            ;
        }

        if ($meetingStartDate->format('d/m/Y') !== $voteStartDate->format('d/m/Y')) {
            $this->context
                ->buildViolation($constraint->messageVoteStartDateInvalid)
                ->atPath('voteStartDate')
                ->addViolation()
            ;
        }

        if ($meetingStartDate >= $meetingEndDate || $meetingEndDate > (clone $meetingStartDate)->modify('+12 hours')) {
            $this->context
                ->buildViolation($constraint->messageMeetingEndDateInvalid)
                ->atPath('meetingEndDate')
                ->addViolation()
            ;
        }

        if ($meetingStartDate > $date = (new \DateTime())->setTimestamp($this->meetingMaxStartDate)) {
            $this->context
                ->buildViolation($constraint->messageMeetingStartDateTooFarAway)
                ->atPath('meetingStartDate')
                ->setParameter('{{date}}', $date->format('d/m/Y'))
                ->addViolation()
            ;
        }
    }
}
