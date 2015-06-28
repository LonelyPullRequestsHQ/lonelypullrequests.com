<?php

namespace LonelyPullRequests\Infrastructure\Service;

use LonelyPullRequests\Domain\Repository\NotificationRepository;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\Service\SyncService;
use Mockery;
use PHPUnit_Framework_TestCase;

class PullRequestSyncServiceTest extends PHPUnit_Framework_TestCase
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
     * @var SyncService
     */
    private $syncService;

    /**
     * Setup is ran for every test
     */
    public function setUp()
    {
        $this->pullRequestsRepository = Mockery::mock('LonelyPullRequests\Domain\Repository\PullRequestsRepository');
        $this->notificationRepository = Mockery::mock('LonelyPullRequests\Domain\Repository\NotificationRepository');

        $this->syncService = new PullRequestSyncService($this->pullRequestsRepository, $this->notificationRepository);
    }

    public function testGetters()
    {
        $this->assertSame($this->pullRequestsRepository, $this->syncService->pullRequestsRepository());
        $this->assertSame($this->notificationRepository, $this->syncService->notificationRepository());
    }
}