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

        $pullRequests = $this->repository->getByRepositoryName($pullRequest->repositoryName());
        $this->assertNull($pullRequests);

        $this->repository->add($pullRequest);

        $pullRequests = $this->repository->getByRepositoryName($pullRequest->repositoryName());
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
}