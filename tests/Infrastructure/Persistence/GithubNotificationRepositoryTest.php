<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use DateTime;
use LonelyPullRequests\Domain\PullRequestState;
use Mockery;
use PHPUnit_Framework_TestCase;

class GithubNotificationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GithubNotificationRepository $repository
     */
    private $repository;

    /**
     * @var Mockery\MockInterface
     */
    private $notificationService;

    private $notificationsMock;

    private $pullRequestsMock;

    public function setUp()
    {
        $key = 'foobar';

        $notifications = Mockery::mock('Github\Api\Notifications');
        $this->notificationsMock = $notifications;

        $pullRequest = Mockery::mock('Github\Api\PullRequest');
        $this->pullRequestsMock = $pullRequest;

        $client = Mockery::mock('Github\Client');
        $client->shouldReceive('authenticate')->withArgs([$key, null, $client::AUTH_HTTP_TOKEN])->andReturnNull();
        $client->shouldReceive('notifications')->andReturn($notifications);
        $client->shouldReceive('pullRequest')->andReturn($pullRequest);

        $this->repository = new GithubNotificationRepository($client, $key);
        $this->notificationService = $notifications;
    }

    public function testAllEmpty()
    {
        $this->notificationsMock->shouldReceive('all')->andReturn([]);
        $notifications = $this->repository->all();
        $this->assertInstanceOf('\LonelyPullRequests\Domain\Notifications', $notifications);
    }

    public function testAllWithOne()
    {
        $notificationData = array(
            array( // Valid PullRequest
                'updated_at' => '20150101',
                'repository' => array(
                    'full_name' => 'foo/bar',
                ),
                'subject' => array(
                    'title' => 'FooBar',
                    'url' => 'http://www.example.com/repos/foo/bar/pulls/1',
                    'type' => 'PullRequest',
                ),

            ),
            array( // Issue and not PullRequest
                'updated_at' => '20150101',
                'repository' => array(
                    'full_name' => 'foo/bar',
                ),
                'subject' => array(
                    'title' => 'FooBar',
                    'url' => 'http://www.example.com/repos/foo/bar/issues/2',
                    'type' => 'Issue',
                ),

            ),
        );

        $this->notificationsMock->shouldReceive('all')->andReturn($notificationData);
        $this->pullRequestsMock->shouldReceive('show')->andReturn([
            'state' => PullRequestState::STATE_OPEN,
        ]);

        $foundNotifications = $this->repository->all();

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Notifications', $foundNotifications);

    }

    public function testMarkRead()
    {
        $dateTime = new DateTime('now');

        $this->notificationService
            ->shouldReceive('markRead')
            ->withArgs([$dateTime]);

        $this->repository->markRead($dateTime);
    }

    public function testMarkReadWithDateTimeImmutable()
    {
        $dateTime = new \DateTimeImmutable('now');

        $this->notificationService
            ->shouldReceive('markRead')
            ->withAnyArgs();

        $this->repository->markRead($dateTime);
    }
}