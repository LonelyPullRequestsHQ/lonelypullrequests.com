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
     * @expectedExceptionMessage Array does not contain an element with key "summary"
     */
    public function testFailingCreationOnEmptyArray()
    {
        PullRequest::fromArray(array());
    }

    public function testInstantiationWithGetters()
    {
        $summary = 'foobar';
        $repositoryName = 'foo/bar';
        $url = 'https://www.lonelypullrequests.com/';
        $loneliness = 42;

        $pullRequest = PullRequest::fromArray(array(
            'summary' => $summary,
            'repositoryName' => $repositoryName,
            'url' => $url,
            'loneliness' => $loneliness,
        ));

        $this->assertInstanceOf('\LonelyPullRequests\Domain\PullRequest', $pullRequest);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Summary', $pullRequest->summary());
        $this->assertEquals($summary, $pullRequest->summary()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\RepositoryName', $pullRequest->repositoryName());
        $this->assertEquals($repositoryName, $pullRequest->repositoryName()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Url', $pullRequest->url());
        $this->assertEquals($url, $pullRequest->url()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Loneliness', $pullRequest->loneliness());
        $this->assertEquals($loneliness, $pullRequest->loneliness()->toString());
    }
}