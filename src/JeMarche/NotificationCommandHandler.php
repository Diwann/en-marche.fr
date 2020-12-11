<?php

namespace App\JeMarche;

use App\Entity\Jecoute\News;
use App\Firebase\JeMarcheMessaging;
use App\Repository\Jecoute\NewsRepository;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotificationCommandHandler implements MessageHandlerInterface
{
    private $newsRepository;
    private $messaging;

    public function __construct(NewsRepository $newsRepository, JeMarcheMessaging $messaging)
    {
        $this->newsRepository = $newsRepository;
        $this->messaging = $messaging;
    }

    public function __invoke(NotificationCommand $command): void
    {
        if (!$news = $this->getNews($command->getUuid())) {
            return;
        }

        $this->messaging->sendNotification($news->getTitle(), $news->getText());
    }

    private function getNews(UuidInterface $uuid): ?News
    {
        return $this->newsRepository->findOneByUuid($uuid);
    }
}
