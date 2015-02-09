<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\PullRequest;
use PHPUnit_Framework_TestCase;

class InMemoryPullRequestsRepositoryTest extends PHPUnit_Framework_TestCase
{
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

    public function testAdd()
    {
        $pullRequest = PullRequest::fromArray([
            'summary' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $this->assertEmpty($this->repository->all());

        $this->repository->add($pullRequest);
        $this->assertNotEmpty($this->repository->all());
    }
}