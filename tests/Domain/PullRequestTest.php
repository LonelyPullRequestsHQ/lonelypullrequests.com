<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class PullRequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor should be private
     */
    public function testCannotInstantiateExternally()
    {
        $reflection = new \ReflectionClass('\LonelyPullRequests\Domain\PullRequest');
        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Array does not contain an element with key "title"
     */
    public function testFailingCreationOnEmptyArray()
    {
        PullRequest::fromArray(array());
    }

    public function testInstantiationWithGetters()
    {
        $title = 'foobar';
        $repositoryName = 'foo/bar';
        $url = 'https://www.lonelypullrequests.com/';
        $loneliness = 42;

        $pullRequest = PullRequest::fromArray(array(
            'title' => $title,
            'repositoryName' => $repositoryName,
            'url' => $url,
            'loneliness' => $loneliness,
        ));

        $this->assertInstanceOf('\LonelyPullRequests\Domain\PullRequest', $pullRequest);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Title', $pullRequest->title());
        $this->assertEquals($title, $pullRequest->title()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\RepositoryName', $pullRequest->repositoryName());
        $this->assertEquals($repositoryName, $pullRequest->repositoryName()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Url', $pullRequest->url());
        $this->assertEquals($url, $pullRequest->url()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Loneliness', $pullRequest->loneliness());
        $this->assertEquals($loneliness, $pullRequest->loneliness()->toString());
    }

    public function testComparison()
    {
        $url = 'https://www.lonelypullrequests.com/';
        $loneliness = 42;

        $p1 = PullRequest::fromArray(array(
            'title' => 'foo',
            'repositoryName' => 'foo/bar',
            'url' => $url,
            'loneliness' => $loneliness,
        ));

        $p2 = PullRequest::fromArray(array(
            'title' => 'bar',
            'repositoryName' => 'bar/foo',
            'url' => $url,
            'loneliness' => $loneliness,
        ));

        $p3 = PullRequest::fromArray(array(
            'title' => 'foo',
            'repositoryName' => 'foo/bar',
            'url' => $url,
            'loneliness' => $loneliness,
        ));

        $this->assertTrue($p1->compareTo($p1));
        $this->assertFalse($p1->compareTo($p2));
        $this->assertTrue($p1->compareTo($p3));

        $this->assertFalse($p2->compareTo($p1));
        $this->assertTrue($p2->compareTo($p2));
        $this->assertFalse($p2->compareTo($p3));

        $this->assertTrue($p3->compareTo($p1));
        $this->assertFalse($p3->compareTo($p2));
        $this->assertTrue($p3->compareTo($p3));

    }
}