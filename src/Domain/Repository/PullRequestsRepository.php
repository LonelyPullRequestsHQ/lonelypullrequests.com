<?php

namespace LonelyPullRequests\Domain\Repository;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;

interface PullRequestsRepository
{
    /**
     * @param PullRequest $pullRequest
     */
    public function add(PullRequest $pullRequest);

    /**
     * @return PullRequests
     */
    public function all();
}
