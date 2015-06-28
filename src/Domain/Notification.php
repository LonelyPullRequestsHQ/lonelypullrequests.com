<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;
use DateTimeImmutable;
use DateTimeInterface;

final class Notification
{
    /**
     * @var RepositoryName
     */
    private $repositoryName;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var DateTimeInterface
     */
    private $eventDateTime;

    /**
     * @var PullRequestState
     */
    private $pullRequestState;

    /**
     * @param $array
     *
     * @return Notification
     */
    public static function fromArray($array)
    {
        Ensure::keyExists($array, 'repositoryName');
        Ensure::keyExists($array, 'title');
        Ensure::keyExists($array, 'url');
        Ensure::keyExists($array, 'eventDateTime');
        Ensure::keyExists($array, 'pullRequestState');

        return new self(
            RepositoryName::fromString($array['repositoryName']),
            Title::fromString($array['title']),
            Url::fromString($array['url']),
            new DateTimeImmutable($array['eventDateTime']),
            PullRequestState::fromString($array['pullRequestState'])
        );
    }

    /**
     * @param RepositoryName     $repositoryName
     * @param Title              $title
     * @param Url                $url
     * @param \DateTimeInterface $eventDateTime
     * @param PullRequestState   $pullRequestState
     */
    private function __construct(RepositoryName $repositoryName, Title $title, Url $url, DateTimeInterface $eventDateTime, PullRequestState $pullRequestState)
    {
        $this->repositoryName = $repositoryName;
        $this->title = $title;
        $this->url = $url;
        $this->eventDateTime = $eventDateTime;
        $this->pullRequestState = $pullRequestState;
    }

    /**
     * @return RepositoryName
     */
    public function repositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * @return Title
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return Url
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @return DateTimeInterface
     */
    public function eventDateTime()
    {
        return $this->eventDateTime;
    }

    /**
     * @param Loneliness $loneliness
     *
     * @return PullRequest
     */
    public function pullRequest(Loneliness $loneliness)
    {
        return PullRequest::create($this->title(), $this->repositoryName(), $this->url(), $loneliness);
    }

    /**
     * @return PullRequestState
     */
    public function pullRequestState()
    {
        return $this->pullRequestState;
    }
}
