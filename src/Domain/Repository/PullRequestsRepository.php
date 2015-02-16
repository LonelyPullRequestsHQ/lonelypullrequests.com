<?php

namespace LonelyPullRequests\Domain\Repository;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;
use LonelyPullRequests\Domain\RepositoryName;

interface PullRequestsRepository
{
    /**
     * @param PullRequest $pullRequest
     */
    public function add(PullRequest $pullRequest);

    /**
     * @param RepositoryName $repositoryName
     *
     * @return PullRequest|null
     */
    public function getByRepositoryName(RepositoryName $repositoryName);

    /**
     * @return PullRequests
     */
    public function all();
}
