<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use Assert\Assertion as Ensure;
use DateTime;
use Github\Client;
use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\Notifications;
use LonelyPullRequests\Domain\Repository\NotificationRepository;

final class GithubNotificationRepository implements NotificationRepository
{
    /**
     * @var \Github\Api\Notification
     */
    private $notificationService;

    /**
     * @var \Github\Api\PullRequest
     */
    private $pullRequestService;

    /**
     * @param \Github\Client $client
     * @param string         $apiKey
     *
     */
    public function __construct(Client $client, $apiKey)
    {
        $client->authenticate($apiKey, null, Client::AUTH_HTTP_TOKEN);

        $this->notificationService = $client->notifications();
        $this->pullRequestService = $client->pullRequest();
    }

    /**
     * @param bool $includingRead
     *
     * @return Notifications
     */
    public function all($includingRead = false)
    {
        $notifications = array();

        foreach ($this->notificationService->all($includingRead) as $notificationStruct) {
            $notification = $this->createNotificationFromStruct($notificationStruct);
            if ($notification instanceof Notification) {
                $notifications[] = $notification;
            }
        }

        return new Notifications($notifications);
    }

    /**
     * @param \DateTimeInterface $since
     */
    public function markRead(\DateTimeInterface $since)
    {
        // NotificationService is PHP 5.3 compatible, so DateTime is needed
        if (!($since instanceof DateTime)) {
            $since = new DateTime($since->format(DateTime::ISO8601));
        }
        $this->notificationService->markRead($since);
    }

    /**
     * @param array $notificationStruct
     *
     * @return Notification|null
     */
    private function createNotificationFromStruct($notificationStruct)
    {
        Ensure::keyExists($notificationStruct, 'updated_at');

        Ensure::keyExists($notificationStruct, 'repository');
        Ensure::keyExists($notificationStruct['repository'], 'full_name');

        Ensure::keyExists($notificationStruct, 'subject');
        Ensure::keyExists($notificationStruct['subject'], 'title');
        Ensure::keyExists($notificationStruct['subject'], 'url');
        Ensure::keyExists($notificationStruct['subject'], 'type');

        if ($notificationStruct['subject']['type'] !== 'PullRequest') {
            return null;
        }

        // Retrieve more information about the pullrequest
        preg_match('#repos/(?P<username>.*)/(?P<repository>.*)/pulls/(?P<issueId>\d+)$#', $notificationStruct['subject']['url'], $matches);

        Ensure::keyExists($matches, 'username');
        Ensure::keyExists($matches, 'repository');
        Ensure::keyExists($matches, 'issueId');

        $pullRequestInformation = $this->pullRequestService->show($matches['username'], $matches['repository'], $matches['issueId']);
        $pullRequestState = $pullRequestInformation['state'];

        // Translate Github API url to public website url
        $url = str_replace('https://api.github.com/repos/', 'https://github.com/', $notificationStruct['subject']['url']);
        $url = str_replace('/pulls/', '/pull/', $url);

        return Notification::fromArray([
            'repositoryName' => $notificationStruct['repository']['full_name'],
            'title' => $notificationStruct['subject']['title'],
            'url' => $url,
            'eventDateTime' => $notificationStruct['updated_at'],
            'pullRequestState' => $pullRequestState
        ]);
    }
}
