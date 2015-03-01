<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\RepositoryName;
use LonelyPullRequests\Domain\Title;

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
     * @param PullRequest $pullRequest
     *
     * @return boolean
     */
    public function has(PullRequest $pullRequest)
    {
        return $this->pullRequests->has($pullRequest);
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return boolean
     */
    public function remove(PullRequest $pullRequest)
    {
        $this->pullRequests = $this->pullRequests->remove($pullRequest);
        return true;
    }

    /**
     * {{@inheritdoc}}
     */
    public function getByRepositoryNameTitle(RepositoryName $repositoryName, Title $title)
    {
        foreach ($this->pullRequests as $pullRequest) {
            if ($pullRequest->repositoryName() == $repositoryName && $pullRequest->title() == $title) {
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
