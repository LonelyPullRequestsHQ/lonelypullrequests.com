<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;

final class InMemoryPullRequestsRepository implements PullRequestsRepository
{
    /**
     * @var PullRequests
     */
    private $pullRequests;

    public function __construct()
    {
        $this->pullRequests = new PullRequests();
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return PullRequests
     */
    public function add(PullRequest $pullRequest)
    {
        $pullRequests = array();
        foreach($this->pullRequests as $requests) {
            $pullRequests[] = $requests;
        }
        $pullRequests[] = $pullRequest;

        $this->pullRequests = new PullRequests($pullRequests);
    }

    /**
     * @return PullRequests
     */
    public function all()
    {
        return $this->pullRequests;
    }
}
