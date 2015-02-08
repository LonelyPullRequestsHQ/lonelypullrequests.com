<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class PullRequests implements \IteratorAggregate
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

    public function add(PullRequest $pullRequest)
    {
        $this->pullRequests[] = $pullRequest;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->pullRequests);
    }
}
