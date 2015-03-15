<?php

/**
 * PullRequestState.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

/**
 * Class PullRequestState models all the state a pull request can be in
 *
 * @package LonelyPullRequests\Domain
 */
final class PullRequestState
{
    /**
     * @const string
     */
    const STATE_OPEN = 'open';

    /**
     * @const string
     */
    const STATE_CLOSED = 'closed';

    /**
     * @param string $state
     */
    private function __construct($state)
    {
        Ensure::string($state, "Pull request state is not of type string");
        Ensure::inArray($state, [
            self::STATE_OPEN,
            self::STATE_CLOSED,
        ], "Unknown pull request state");

        $this->state = $state;
    }

    /**
     * @param string $pullRequestState
     *
     * @return PullRequestState
     */
    public static function fromString($pullRequestState)
    {
        return new self($pullRequestState);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * This method compares a given state with the current
     *
     * @param string $pullRequestState
     *
     * @return bool
     */
    public function is($pullRequestState)
    {
        return self::fromString($pullRequestState)->toString() === $this->toString();
    }
}
