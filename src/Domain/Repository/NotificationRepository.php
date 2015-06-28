<?php

namespace LonelyPullRequests\Domain\Repository;

use LonelyPullRequests\Domain\Notifications;

interface NotificationRepository
{
    /**
     * @param bool $includingRead
     *
     * @return Notifications
     */
    public function all($includingRead);

    /**
     * @param \DateTimeInterface $since
     */
    public function markRead(\DateTimeInterface $since);
}
