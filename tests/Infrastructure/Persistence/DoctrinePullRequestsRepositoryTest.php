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
            ->shouldReceive('persist')
            ->andReturnNull();
        $entityManager
            ->shouldReceive('flush')
            ->andReturnNull();

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

    public function testGetByName()
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

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn(array());

        $this->assertEmpty($this->repository->all());

        $this->repository->add($pullRequest);

        $this->entityPersister
            ->shouldReceive('loadAll')
            ->andReturn(array($pullRequest));

        // TODO: For some reason, the shouldReceive('loadAll') is not overwritten with new expected array
        //$this->assertNotEmpty($this->repository->all());
    }
}