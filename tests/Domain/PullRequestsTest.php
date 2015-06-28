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

    public function testHasMethod()
    {
        $pullRequest1 = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $pullRequest2 = PullRequest::fromArray([
            'title' => 'b',
            'repositoryName' => 'bar/foo',
            'url' => 'http://www.examplefdsa.com/',
            'loneliness' => 424
        ]);

        $pullRequest3 = PullRequest::fromArray([
            'title' => 'c',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.exafdsample.com/',
            'loneliness' => 13
        ]);

        $pullRequests = new PullRequests([$pullRequest1, $pullRequest2]);

        $this->assertTrue($pullRequests->has($pullRequest1));
        $this->assertTrue($pullRequests->has($pullRequest2));
        $this->assertFalse($pullRequests->has($pullRequest3));
    }

    public function testRemoveMethod()
    {
        $pullRequest1 = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $pullRequest2 = PullRequest::fromArray([
            'title' => 'b',
            'repositoryName' => 'bar/foo',
            'url' => 'http://www.examplefdsa.com/',
            'loneliness' => 424
        ]);

        $pullRequest3 = PullRequest::fromArray([
            'title' => 'c',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.exafdsample.com/',
            'loneliness' => 13
        ]);

        $pullRequests = new PullRequests([$pullRequest1, $pullRequest2, $pullRequest3]);
        $pullRequests = $pullRequests->remove($pullRequest2);

        $this->assertTrue($pullRequests->has($pullRequest1));
        $this->assertFalse($pullRequests->has($pullRequest2));
        $this->assertTrue($pullRequests->has($pullRequest3));
    }

    public function testOverrideSame()
    {
        $pullRequest1 = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $pullRequest2 = PullRequest::fromArray([
            'title' => 'b',
            'repositoryName' => 'bar/foo',
            'url' => 'http://www.examplefdsa.com/',
            'loneliness' => 424
        ]);

        $pullRequest3 = PullRequest::fromArray([
            'title' => 'c',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.exafdsample.com/',
            'loneliness' => 13
        ]);

        $pullRequests = new PullRequests([$pullRequest1, $pullRequest2, $pullRequest3]);

        $alteredPullRequests2 = PullRequest::fromArray([
            'title' => $pullRequest2->title()->toString(),
            'repositoryName' => $pullRequest2->repositoryName()->toString(),
            'url' => 'http://example.com/some-other-value',
            'loneliness' => 9999,
        ]);

        $pullRequests = $pullRequests->add($alteredPullRequests2);

        $this->assertTrue($pullRequests->has($pullRequest1));
        $this->assertTrue($pullRequests->has($pullRequest2));
        $this->assertTrue($pullRequests->has($pullRequest3));

        $this->assertCount(3, $pullRequests);
    }
}