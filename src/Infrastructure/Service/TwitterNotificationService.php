<?php

namespace LonelyPullRequests\Infrastructure\Service;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\Service\NotificationService;
use TwitterAPIExchange;

final class TwitterNotificationService implements NotificationService
{
    /**
     * @var \TwitterAPIExchange
     */
    private $twitter;

    /**
     * @param TwitterAPIExchange $twitter
     */
    public function __construct(TwitterAPIExchange $twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return null
     */
    public function notify(PullRequest $pullRequest)
    {
        return $this->twitter
                    ->buildOauth('https://api.twitter.com/1.1/statuses/update.json', 'POST')
                    ->setPostfields([
                        'status' => 'Feedback would be welcome on this pull request! ' . $pullRequest->url()->toString()
                    ])
                    ->performRequest();
    }
}