<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\RepositoryName;
use Mockery;
use PHPUnit_Framework_TestCase;

class DoctrinePullRequestsRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrinePullRequestsRepository
     */
    private $repository;
    private $entityManager;
    private $entityPersister;
    private $objects = array();

    public function setUp()
    {
        $this->objects = array();

        $this->entityPersister = Mockery::mock('EntityPersistor');

        $unitOfWork = Mockery::mock('Doctrine\ORM\UnitOfWork');
        $unitOfWork
            ->shouldReceive('getEntityPersister')
            ->andReturn($this->entityPersister);

        $entityManager = Mockery::mock('Doctrine\ORM\EntityManagerInterface');
        $entityManager
            ->shouldReceive('getUnitOfWork')
            ->andReturn($unitOfWork);
        $entityManager
            ->shouldReceive('merge')
            ->andReturnNull();
        $entityManager
            ->shouldReceive('flush')
            ->andReturnNull();
        $this->entityManager = $entityManager;

        $classMetadata = new \Doctrine\ORM\Mapping\ClassMetadata('\LonelyPullRequests\Domain\PullRequest');
        $this->repository = new DoctrinePullRequestsRepository($entityManager, $classMetadata);
    }

    public function testAll()
    {
        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn($this->objects);

        $pullRequests = $this->repository->all();
        $this->assertInstanceOf('\LonelyPullRequests\Domain\PullRequests', $pullRequests);
    }

    public function testGetByRepositoryNameTitle()
    {
        $pullRequest = PullRequest::fromArray([
            'title' => 'foobarbaz',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42,
        ]);

        $this->entityPersister
            ->shouldReceive('load')
            ->andReturn($pullRequest);

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

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn(array())->byDefault();

        $this->assertEmpty($this->repository->all());

        $this->repository->add($pullRequest);

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn(array($pullRequest));

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

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn(array())->byDefault();

        $this->assertEmpty($this->repository->all());

        $this->entityPersister
            ->shouldReceive('load')
            ->andReturn(null)
            ->byDefault();
        $this->assertFalse($this->repository->has($pullRequest));

        $this->repository->add($pullRequest);

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn(array($pullRequest));

        $this->assertNotEmpty($this->repository->all());

        $this->entityPersister
            ->shouldReceive('load')
            ->andReturn($pullRequest);

        $this->assertTrue($this->repository->has($pullRequest));
    }

    public function testRemove()
    {
        $pullRequest = PullRequest::fromArray([
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ]);

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn(array())->byDefault();

        $this->assertEmpty($this->repository->all());

        $this->repository->add($pullRequest);

        $this->entityPersister
            ->shouldReceive('load')
            ->andReturn($pullRequest);

        $this->entityManager
            ->shouldReceive('remove')
            ->andReturn(null);

        $this->repository->remove($pullRequest);

        $this->assertEmpty($this->repository->all());
    }

    public function testPersistUpsert()
    {
        $testStruct = [
            'title' => 'a',
            'repositoryName' => 'foo/bar',
            'url' => 'http://www.example.com/',
            'loneliness' => 42
        ];

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn($this->objects);

        $pullRequest = PullRequest::fromArray($testStruct);
        $this->repository->add($pullRequest);

        $pullRequest = PullRequest::fromArray($testStruct);
        $this->repository->add($pullRequest);
    }
}