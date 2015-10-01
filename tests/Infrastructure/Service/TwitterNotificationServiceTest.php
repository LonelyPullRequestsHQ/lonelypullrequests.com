<?php

namespace LonelyPullRequests\Infrastructure\Service;

use LonelyPullRequests\Domain\PullRequest;
use Mockery;
use PHPUnit_Framework_TestCase;

class TwitterNotificationServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface|\TwitterAPIExchange
     */
    private $twitterApiExchange;

    /**
     * @var TwitterNotificationService
     */
    private $notificationService;

    /**
     * Setup is ran for every test
     */
    public function setUp()
    {
        $this->twitterApiExchange = Mockery::mock('TwitterAPIExchange');
        $this->notificationService = new TwitterNotificationService($this->twitterApiExchange);

        $this->twitterApiExchange
            ->shouldReceive('buildOauth')
            ->once()
            ->withArgs(['https://api.twitter.com/1.1/statuses/update.json', 'POST'])
            ->andReturnSelf();

        $this->twitterApiExchange
            ->shouldReceive('performRequest')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

    }

    public function testNotify()
    {
        $pullRequest = PullRequest::fromArray([
            'title' => 'Hello Foo',
            'repositoryName' => 'foo/bar',
            'url' => 'https://www.github.com/foo/bar',
            'loneliness' => 0
        ]);

        $this->twitterApiExchange
            ->shouldReceive('setPostfields')
            ->once()
            ->withArgs([[
               'status' => 'Feedback would be welcome on this pull request! ' . $pullRequest->url()->toString()
            ]])
            ->andReturnSelf();

        $this->notificationService->notify($pullRequest);
    }
}