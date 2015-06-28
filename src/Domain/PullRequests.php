<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;
use Traversable;

final class PullRequests implements \IteratorAggregate, \Countable
{
    /**
     * @var PullRequest[]
     */
    private $pullRequests = [];

    /**
     * @param PullRequest[] $pullRequests
     */
    public function __construct(array $pullRequests = [])
    {
        Ensure::allIsInstanceOf($pullRequests, PullRequest::class);

        foreach($pullRequests as $pullRequest) {
            $this->pullRequests[$pullRequest->identifier()] = $pullRequest;
        }
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return PullRequests
     */
    public function add(PullRequest $pullRequest)
    {
        $newPullRequests = $this->pullRequests;

        if($this->has($pullRequest)) {
            $newPullRequests[$pullRequest->identifier()] = $pullRequest;
        } else {
            $newPullRequests[] = $pullRequest;
        }

        return new PullRequests($newPullRequests);
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return bool
     */
    public function has(PullRequest $pullRequest)
    {
        return isset($this->pullRequests[$pullRequest->identifier()]);
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return PullRequests
     */
    public function remove(PullRequest $pullRequest)
    {
        $newPullRequests = $this->pullRequests;
        unset($newPullRequests[$pullRequest->identifier()]);
        return new PullRequests($newPullRequests);
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->pullRequests);
    }

    /**
     * @see Countable::count
     */
    public function count()
    {
        return count($this->pullRequests);
    }
}
