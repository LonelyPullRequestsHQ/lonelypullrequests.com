<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\RepositoryName;

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
        $this->pullRequests = $this->pullRequests->add($pullRequest);
    }

    /**
     * {{@inheritdoc}}
     */
    public function getByRepositoryName(RepositoryName $repositoryName)
    {
        foreach ($this->pullRequests as $pullRequest) {
            if ($pullRequest->repositoryName() == $repositoryName) {
                return $pullRequest;
            }
        }

        return null;
    }


    /**
     * @return PullRequests
     */
    public function all()
    {
        return $this->pullRequests;
    }
}
