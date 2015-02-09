<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\Notifications;
use LonelyPullRequests\Domain\Repository\NotificationRepository;

final class GithubNotificationRepository implements NotificationRepository
{
    /**
     * @var Notifications
     */
    private $notifications;

    public function __construct()
    {
        $this->notifications = new Notifications();
    }

    /**
     * @param Notification $pullRequest
     *
     * @return Notifications
     */
    public function add(Notification $notification)
    {
        $this->notifications = $this->notifications->add($notification);
    }

    /**
     * @return Notifications
     */
    public function all()
    {
        return $this->notifications;
    }
}
