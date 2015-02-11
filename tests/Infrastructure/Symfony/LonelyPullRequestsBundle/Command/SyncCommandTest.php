<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use Mockery;
use PHPUnit_Framework_TestCase;

class SyncCommandTest extends PHPUnit_Framework_TestCase
{
    public function testConfigTreeBuilder()
    {
        $inputInterface = Mockery::mock('Symfony\Component\Console\Input\InputInterface');
        $outputInterface = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');

        $inputInterface
            ->shouldReceive('bind')
            ->andReturnNull();

        $inputInterface
            ->shouldReceive('isInteractive')
            ->andReturn(false);
        $inputInterface
            ->shouldReceive('validate')
            ->andReturn();
        $outputInterface
            ->shouldReceive('writeln')
            ->andReturn();

        $command = new SyncCommand();
        //$command->run($inputInterface, $outputInterface);
    }
}