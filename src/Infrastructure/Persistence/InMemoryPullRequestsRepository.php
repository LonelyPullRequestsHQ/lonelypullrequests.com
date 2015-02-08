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
     */
    public function add(PullRequest $pullRequest)
    {
        $this->pullRequests->add($pullRequest);
    }

    /**
     * @return PullRequests
     */
    public function all()
    {
        return $this->pullRequests;
    }
}
