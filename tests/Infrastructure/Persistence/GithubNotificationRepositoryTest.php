<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\Notification;
use PHPUnit_Framework_TestCase;

class GithubNotificationRepositoryTest extends PHPUnit_Framework_TestCase
{
    private $repository;

    public function setUp()
    {
        $this->repository = new GithubNotificationRepository();
    }

    public function testAll()
    {
        $notifications = $this->repository->all();
        $this->assertInstanceOf('\LonelyPullRequests\Domain\Notifications', $notifications);
    }

    public function testAdd()
    {
        $notification = Notification::fromArray([]);

        $this->assertEmpty($this->repository->all());

        $this->repository->add($notification);
        $this->assertNotEmpty($this->repository->all());
    }
}