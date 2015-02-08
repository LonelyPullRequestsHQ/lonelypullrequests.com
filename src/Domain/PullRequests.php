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
    private function add(PullRequest $pullRequest)
    {
        $this->pullRequests[] = $pullRequest;

        return $this;
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->pullRequests);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return sizeof($this->pullRequests);
    }
}
