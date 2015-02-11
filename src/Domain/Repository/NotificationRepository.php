<?php

namespace LonelyPullRequests\Domain\Repository;

use LonelyPullRequests\Domain\Notifications;

interface NotificationRepository
{
    /**
     * @return Notifications
     */
    public function all();

    /**
     * @param \DateTimeInterface $since
     */
    public function markRead(\DateTimeInterface $since);
}
