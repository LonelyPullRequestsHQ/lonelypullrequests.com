<?php

namespace LonelyPullRequests\Domain\Service;

use LonelyPullRequests\Domain\Repository\NotificationRepository;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;

/**
 * Interface SyncService
 *
 * @package LonelyPullRequests\Domain\Service
 */
interface SyncService
{
    /**
     * Synchronizes the notification repository with the pullrequest repository
     *
     * @param bool $commit
     *
     * @return null
     */
    public function sync($commit = false);

    /**
     * @return PullRequestsRepository
     */
    public function pullRequestsRepository();

    /**
     * @return NotificationRepository
     */
    public function notificationRepository();
}
