<?php

namespace LonelyPullRequests\Infrastructure\Service;

use LonelyPullRequests\Domain\Loneliness;
use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\PullRequestState;
use LonelyPullRequests\Domain\Repository\NotificationRepository;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\Service\SyncService;

/**
 * Class PullRequestSyncService
 *
 * @package LonelyPullRequests\Infrastructure\Service
 */
final class PullRequestSyncService implements SyncService
{
    /**
     * @var PullRequestsRepository
     */
    private $pullRequestsRepository;

    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var Notification|null
     */
    private $lastParsedNotification;

    /**
     * @param PullRequestsRepository $pullRequestsRepository
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(PullRequestsRepository $pullRequestsRepository, NotificationRepository $notificationRepository)
    {
        $this->pullRequestsRepository = $pullRequestsRepository;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @return PullRequestsRepository
     */
    public function pullRequestsRepository()
    {
        return $this->pullRequestsRepository;
    }

    /**
     * @return NotificationRepository
     */
    public function notificationRepository()
    {
        return $this->notificationRepository;
    }

    /**
     * Synchronizes the notification repository with the pullrequest repository
     *
     * @param bool $commit
     * @param bool $includingRead
     *
     * @return void
     */
    public function sync($commit = false, $includingRead = false)
    {
        $notifications = $this->notificationRepository()->all($includingRead);

        /** @var Notification $notification */
        foreach($notifications as $notification) {
            $this->syncNotification($notification);
        }

        $this->notifyParsedLastEvent($commit);
    }

    /**
     * @param Notification $notification
     */
    private function syncNotification(Notification $notification)
    {
        $pullRequest = $notification->pullRequest(Loneliness::fromInteger(0));

        if($notification->pullRequestState()->is(PullRequestState::STATE_OPEN)) {
            $this->pullRequestsRepository()->add($pullRequest);
        } else {
            $this->pullRequestsRepository()->remove($pullRequest);
        }

        $this->lastParsedNotification = $notification;
    }

    /**
     * @param boolean $commit
     */
    private function notifyParsedLastEvent($commit)
    {
        $lastParsedNotification = $this->lastParsedNotification;

        if($commit && $lastParsedNotification !== null) {
            $this->notificationRepository()->markRead($lastParsedNotification->eventDateTime());
        }
    }
}
