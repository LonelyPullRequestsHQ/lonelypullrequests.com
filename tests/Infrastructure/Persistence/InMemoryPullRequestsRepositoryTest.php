<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\PullRequest;
use PHPUnit_Framework_TestCase;

class InMemoryPullRequestsRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InMemoryPullRequestsRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = new InMemoryPullRequestsRepository();
    }

    public function testAll()
    {
        $pullRequests = $this->repository->all();
        $this->assertInstanceOf('\LonelyPullRequests\Domain\PullRequests', $pullRequests);
    }

    public function testGetByName()
    {
        $pullRequest = PullRequest::fromArray([
            'title' => 'foobarbaz',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42,
        ]);

        $pullRequests = $this->repository->getByRepositoryNameTitle($pullRequest->repositoryName(), $pullRequest->title());
        $this->assertNull($pullRequests);

        $this->repository->add($pullRequest);

        $pullRequests = $this->repository->getByRepositoryNameTitle($pullRequest->repositoryName(), $pullRequest->title());
        $this->assertInstanceOf('\LonelyPullRequests\Domain\PullRequest', $pullRequests);
    }

    public function testAdd()
    {
        $pullRequest = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $this->assertEmpty($this->repository->all());

        $this->repository->add($pullRequest);
        $this->assertNotEmpty($this->repository->all());
    }

    public function testHas()
    {
        $pullRequest = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $this->assertFalse($this->repository->has($pullRequest));
        $this->repository->add($pullRequest);
        $this->assertTrue($this->repository->has($pullRequest));
    }

    public function testDelete()
    {
        $pullRequest = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $this->assertEmpty($this->repository->all());

        $this->repository->add($pullRequest);
        $this->assertNotEmpty($this->repository->all());

        $this->repository->remove($pullRequest);
        $this->assertEmpty($this->repository->all());
    }
}