<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\Notifications;
use Mockery;
use PHPUnit_Framework_TestCase;

class SyncCommandTest extends PHPUnit_Framework_TestCase
{
    private $inputInterface;
    private $outputInterface;
    private $container;

    private $pullRequestRepository;
    private $notificationRepository;

    public function setUp()
    {
        $inputInterface = Mockery::mock('Symfony\Component\Console\Input\InputInterface');
        $outputInterface = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');
        $container = Mockery::mock('Symfony\Component\DependencyInjection\ContainerInterface');

        $pullRequestRepository = Mockery::mock('LonelyPullRequests\Domain\Repository\PullRequestsRepository');
        $notificationRepository = Mockery::mock('LonelyPullRequests\Domain\Repository\NotificationRepository');

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

        $outputInterface
            ->shouldReceive('writeln')
            ->andReturn();

        $container
            ->shouldReceive('get')
            ->withArgs(['lonely_pull_requests.repository.pull_requests'])
            ->andReturn($pullRequestRepository);

        $container
            ->shouldReceive('get')
            ->withArgs(['lonely_pull_requests.repository.notification'])
            ->andReturn($notificationRepository);

        $this->inputInterface = $inputInterface;
        $this->outputInterface = $outputInterface;
        $this->container = $container;

        $this->pullRequestRepository = $pullRequestRepository;
        $this->notificationRepository = $notificationRepository;

    }

    public function testSyncCommandWithoutNotifications()
    {
        $this->notificationRepository
            ->shouldReceive('all')
            ->withNoArgs()
            ->andReturn(new Notifications());

        $this->runCommand();
    }

    public function testSyncCommandWithNotifications()
    {
        $this->notificationRepository
            ->shouldReceive('all')
            ->withNoArgs()
            ->andReturn(new Notifications([
                Notification::fromArray(array(
                    'title' => 'foobar',
                    'repositoryName' => 'foo/bar',
                    'url' => 'http://www.example.com/',
                    'eventDateTime' => 'now'
                ))
        ]));

        $this->notificationRepository
            ->shouldReceive('markRead')
            ->andReturnNull();

        $this->pullRequestRepository
            ->shouldReceive('add')
            ->andReturnNull();

        $this->runCommand();
    }

    private function runCommand()
    {
        $command = new SyncCommand();
        $command->setContainer($this->container);
        $command->run($this->inputInterface, $this->outputInterface);
    }
}