<?php

namespace LonelyPullRequests\Infrastructure\Service;

use LonelyPullRequests\Domain\Loneliness;
use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\PullRequestState;
use LonelyPullRequests\Domain\Repository\NotificationRepository;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\Service\NotificationService;
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
     * @var NotificationService
     */
    private $notificationService;

    /**
     * @param PullRequestsRepository $pullRequestsRepository
     * @param NotificationRepository $notificationRepository
     * @param NotificationService $notificationService
     */
    public function __construct(
        PullRequestsRepository $pullRequestsRepository,
        NotificationRepository $notificationRepository,
        NotificationService $notificationService
    ) {
        $this->pullRequestsRepository = $pullRequestsRepository;
        $this->notificationRepository = $notificationRepository;
        $this->notificationService = $notificationService;
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
     * @return NotificationService
     */
    public function notificationService()
    {
        return $this->notificationService;
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
            $this->syncNotification($notification, $commit);
        }

        $this->notifyParsedLastEvent($commit);
    }

    /**
     * @param Notification $notification
     * @param bool         $commit
     */
    private function syncNotification(Notification $notification, $commit = false)
    {
        $pullRequest = $notification->pullRequest(Loneliness::fromInteger(0));

        $firstSeen = false;
        $existingPullRequest = $this->pullRequestsRepository()
                                    ->getByRepositoryNameTitle($pullRequest->repositoryName(), $pullRequest->title());

        if($existingPullRequest === null) {
            $firstSeen = true;
        }

        if($notification->pullRequestState()->equals(PullRequestState::STATE_OPEN)) {
            $this->pullRequestsRepository()->add($pullRequest);

            if($commit && $firstSeen) {
                $this->notificationService()->notify($pullRequest);
            }
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
