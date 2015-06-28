<?php

namespace LonelyPullRequests\Domain\Repository;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;
use LonelyPullRequests\Domain\RepositoryName;
use LonelyPullRequests\Domain\Title;

interface PullRequestsRepository
{
    /**
     * @param PullRequest $pullRequest
     */
    public function add(PullRequest $pullRequest);

    /**
     * @param PullRequest $pullRequest
     *
     * @return boolean
     */
    public function has(PullRequest $pullRequest);

    /**
     * @param PullRequest $pullRequest
     *
     * @return boolean
     */
    public function remove(PullRequest $pullRequest);

    /**
     * @param RepositoryName $repositoryName
     * @param Title          $title
     *
     * @return PullRequest|null
     */
    public function getByRepositoryNameTitle(RepositoryName $repositoryName, Title $title);

    /**
     * @return PullRequests
     */
    public function all();
}
