<?php

namespace LonelyPullRequests\Domain;

use Assert\AssertionFailedException;
use PHPUnit_Framework_TestCase;

class PullRequestStateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Unknown pull request state
     */
    public function testInvalidEnumThrowsException()
    {
        PullRequestState::fromString('foo');
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Pull request state is not of type string
     */
    public function testInvalidDataTypeThrowsException()
    {
        PullRequestState::fromString([]);
    }

    public function testToString()
    {
        $stateString = PullRequestState::STATE_OPEN;

        $state = PullRequestState::fromString($stateString);
        $this->assertEquals($stateString, (string)$state);
        $this->assertEquals($stateString, $state->toString());
    }

    public function testEqualsSucceedsOnSameType()
    {
        $state = PullRequestState::fromString(PullRequestState::STATE_OPEN);
        $this->assertTrue($state->equals(PullRequestState::STATE_OPEN));
    }
    public function testEqualsFailsOnDifferentType()
    {
        $state = PullRequestState::fromString(PullRequestState::STATE_OPEN);
        $this->assertFalse($state->equals(PullRequestState::STATE_CLOSED));
    }
}