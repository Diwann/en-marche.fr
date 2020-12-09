<?php

namespace App\Repository\Jecoute;

use App\Entity\Jecoute\DataAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class DataAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataAnswer::class);
    }

    public function findAllBySurveyQuestion(UuidInterface $surveyQuestionUuid): array
    {
        return $this
            ->createQueryBuilder('dataAnswer')
            ->select('dataAnswer.textField', 'dataSurvey.postedAt')
            ->innerJoin('dataAnswer.surveyQuestion', 'surveyQuestion')
            ->innerJoin('dataAnswer.dataSurvey', 'dataSurvey')
            ->andWhere('surveyQuestion.uuid = :surveyQuestion')
            ->setParameter('surveyQuestion', $surveyQuestionUuid)
            ->getQuery()
            ->getResult()
        ;
    }
}
