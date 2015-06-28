<?php

namespace LonelyPullRequests\Domain\Service;

use LonelyPullRequests\Domain\PullRequest;

/**
 * Interface NotificationService
 *
 * @package LonelyPullRequests\Domain\Service
 */
interface NotificationService
{
    /**
     * @param PullRequest $pullRequest
     *
     * @return null
     */
    public function notify(PullRequest $pullRequest);
}
