<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use DateTime;
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

    public function setUp()
    {
        $key = 'foobar';

        $notifications = Mockery::mock('Github\Api\Notifications');
        $this->notificationsMock = $notifications;

        $client = Mockery::mock('Github\Client');
        $client->shouldReceive('authenticate')->withArgs([$key, null, $client::AUTH_HTTP_TOKEN])->andReturnNull();
        $client->shouldReceive('notifications')->andReturn($notifications);

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
                    'url' => 'http://www.example.com/',
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
                    'url' => 'http://www.example.com/',
                    'type' => 'Issue',
                ),

            ),
        );

        $this->notificationsMock->shouldReceive('all')->andReturn($notificationData);

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
}