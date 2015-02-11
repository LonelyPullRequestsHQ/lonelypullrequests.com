<?php

namespace LonelyPullRequests\Domain\Repository;

use LonelyPullRequests\Domain\Notification;

interface NotificationRepository
{
    /**
     * @return Notification
     */
    public function all();
}
