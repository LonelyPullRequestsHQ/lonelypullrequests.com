<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\Notifications;
use LonelyPullRequests\Domain\PullRequestState;
use Mockery;
use PHPUnit_Framework_TestCase;

class SyncCommandTest extends PHPUnit_Framework_TestCase
{
    private $inputInterface;
    private $outputInterface;
    private $container;

    /**
     * @var Mockery\MockInterface
     */
    private $syncService;

    public function setUp()
    {
        $inputInterface = Mockery::mock('Symfony\Component\Console\Input\InputInterface');
        $outputInterface = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');
        $container = Mockery::mock('Symfony\Component\DependencyInjection\ContainerInterface');

        $syncService = Mockery::mock('LonelyPullRequests\Domain\Service\PullRequestSyncService');

        $inputInterface
            ->shouldReceive('bind')
            ->andReturnNull();
        $inputInterface
            ->shouldReceive('isInteractive')
            ->andReturn(false);
        $inputInterface
            ->shouldReceive('validate')
            ->andReturn();

        $inputInterface
            ->shouldReceive('getOption')
            ->withArgs(['commit'])
            ->andReturn(true);
        $inputInterface
            ->shouldReceive('getOption')
            ->withArgs(['all'])
            ->andReturn(true);

        $outputInterface
            ->shouldReceive('writeln')
            ->andReturn();

        $container
            ->shouldReceive('get')
            ->withArgs(['lonely_pull_requests.service.sync'])
            ->andReturn($syncService);

        $this->inputInterface = $inputInterface;
        $this->outputInterface = $outputInterface;
        $this->container = $container;

        $this->syncService = $syncService;

    }

    public function testSyncCommand()
    {
        $this->syncService
             ->shouldReceive('sync')
            ->withArgs([true, true])
            ;
        $this->runCommand();
    }

    private function runCommand()
    {
        $command = new SyncCommand();
        $command->setContainer($this->container);
        $command->run($this->inputInterface, $this->outputInterface);
    }
}