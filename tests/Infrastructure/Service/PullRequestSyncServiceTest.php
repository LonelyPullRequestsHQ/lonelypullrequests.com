<?php

namespace LonelyPullRequests\Infrastructure\Service;

use LonelyPullRequests\Domain\Loneliness;
use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\PullRequestState;
use LonelyPullRequests\Domain\Repository\NotificationRepository;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\Service\NotificationService;
use LonelyPullRequests\Domain\Service\SyncService;
use Mockery;
use PHPUnit_Framework_TestCase;

class PullRequestSyncServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface|PullRequestsRepository
     */
    private $pullRequestsRepository;

    /**
     * @var Mockery\MockInterface|NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var Mockery\MockInterface|NotificationService
     */
    private $notificationService;

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
        $this->notificationService = Mockery::mock('LonelyPullRequests\Domain\Service\NotificationService');

        $this->syncService = new PullRequestSyncService($this->pullRequestsRepository, $this->notificationRepository, $this->notificationService);
    }

    public function testGetters()
    {
        $this->assertSame($this->pullRequestsRepository, $this->syncService->pullRequestsRepository());
        $this->assertSame($this->notificationRepository, $this->syncService->notificationRepository());
        $this->assertSame($this->notificationService, $this->syncService->notificationService());
    }

    public function testSyncServiceWithoutNotificationsAndWithoutCommit()
    {
        $this->performSyncForPullRequests(false, false, []);
    }

    public function testSyncServiceWithoutNotificationsAndWithCommitDoesntMarkAsRead()
    {
        $this->performSyncForPullRequests(true, false, []);
    }

    public function testSyncServiceWithSingleNotificationStateOpen()
    {
        $notification = Notification::fromArray([
            'repositoryName' => 'foo/bar',
            'title' => 'Foo',
            'url' => 'http://example.com/',
            'eventDateTime' => (new \DateTime())->format('Y-m-d H:i:s'),
            'pullRequestState' => PullRequestState::STATE_OPEN
        ]);

        $pullRequest = $notification->pullRequest(Loneliness::fromInteger(0));

        $this->pullRequestsRepository
            ->shouldReceive('getByRepositoryNameTitle')
            ->withArgs([$notification->repositoryName(), $notification->title()])
            ->once()
            ->andReturn(null);

        $this->pullRequestsRepository
            ->shouldReceive('add')
            ->withAnyArgs()
            ->once()
            ->andReturnSelf();

        $this->notificationRepository
            ->shouldReceive('markRead')
            ->withArgs([$notification->eventDateTime()])
            ->once();

        $this->notificationService
            ->shouldReceive('notify')
            ->once()
            ->withAnyArgs();

        $this->performSyncForPullRequests(true, false, [$notification]);
    }


    public function testSyncServiceWithSingleNotificationStateClosed()
    {
        $notification = Notification::fromArray([
            'repositoryName' => 'foo/bar',
            'title' => 'Foo',
            'url' => 'http://example.com/',
            'eventDateTime' => (new \DateTime())->format('Y-m-d H:i:s'),
            'pullRequestState' => PullRequestState::STATE_CLOSED
        ]);

        $pullRequest = $notification->pullRequest(Loneliness::fromInteger(0));

        $this->pullRequestsRepository
            ->shouldReceive('getByRepositoryNameTitle')
            ->withArgs([$notification->repositoryName(), $notification->title()])
            ->once()
            ->andReturn($pullRequest);

        $this->pullRequestsRepository
            ->shouldReceive('remove')
            ->withAnyArgs()
            ->once()
            ->andReturnSelf();

        $this->notificationRepository
            ->shouldReceive('markRead')
            ->withArgs([$notification->eventDateTime()])
            ->once();

        $this->performSyncForPullRequests(true, false, [$notification]);
    }

    /**
     * @param bool $commit
     * @param bool $all
     * @param Notification[] $notifications
     */
    private function performSyncForPullRequests($commit, $all, $notifications)
    {
        $this->notificationRepository
            ->shouldReceive('all')
            ->once()
            ->withArgs([$all])
            ->andReturn($notifications);

        $this->syncService->sync($commit, $all);
    }
}