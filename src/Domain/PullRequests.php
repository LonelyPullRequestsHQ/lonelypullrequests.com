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

        foreach ($pullRequests as $pullRequest) {
            $this->add($pullRequest);
        }
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return PullRequests $this
     */
    public function add(PullRequest $pullRequest)
    {
        return new PullRequests(array_merge($this->pullRequests, [$pullRequest]));
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
        return sizeof($this->pullRequests);
    }
}
