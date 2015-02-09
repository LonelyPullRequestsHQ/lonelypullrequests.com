<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class PullRequestsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Class "foo" was expected to be instanceof of "LonelyPullRequests\Domain\PullRequest" but is not.
     */
    public function testArrayContainsPullRequestObjects()
    {
        new PullRequests(array(
            'foo',
            'bar',
            42,
        ));
    }

    public function testEmptyArray()
    {
        $pullRequests = new PullRequests(array());
        $this->assertEmpty($pullRequests);
    }

    public function testImmutability()
    {
        $pullRequests = new PullRequests();
        $pullRequest = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $newPullRequests = $pullRequests->add($pullRequest);
        $anotherPullRequests = $pullRequests->add($pullRequest);

        $this->assertNotSame($pullRequest, $newPullRequests);
        $this->assertNotSame($pullRequest, $anotherPullRequests);
        $this->assertNotSame($newPullRequests, $anotherPullRequests);
        $this->assertEquals($newPullRequests, $anotherPullRequests);
    }

    public function testIterator()
    {
        $pullRequests = new PullRequests();
        $iterator = $pullRequests->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);
    }
}