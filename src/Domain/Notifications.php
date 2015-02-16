<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;
use Traversable;

final class Notifications implements \IteratorAggregate, \Countable
{
    /**
     * @var Notification[]
     */
    private $notifications = [];

    /**
     * @param Notification[] $notifications
     */
    public function __construct(array $notifications = [])
    {
        Ensure::allIsInstanceOf($notifications, Notification::class);

        $this->notifications = array_values($notifications);
    }

    /**
     * @param Notification $notification
     *
     * @return Notifications
     */
    public function add(Notification $notification)
    {
        return new Notifications(array_merge($this->notifications, [$notification]));
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->notifications);
    }

    /**
     * @see Countable::count
     */
    public function count()
    {
        return count($this->notifications);
    }
}
