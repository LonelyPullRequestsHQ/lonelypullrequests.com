<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use Mockery;
use PHPUnit_Framework_TestCase;

class TweetCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    private $inputInterface;

    /**
     * @var Mockery\MockInterface
     */
    private $outputInterface;

    /**
     * @var Mockery\MockInterface
     */
    private $container;

    /**
     * @var Mockery\MockInterface
     */
    private $twitterApiExchange;

    public function setUp()
    {
        $inputInterface = Mockery::mock('Symfony\Component\Console\Input\InputInterface');
        $outputInterface = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');
        $container = Mockery::mock('Symfony\Component\DependencyInjection\ContainerInterface');

        $this->twitterApiExchange = Mockery::mock('TwitterAPIExchange');

        $this->twitterApiExchange
            ->shouldReceive('buildOauth')
            ->once()
            ->withArgs(['https://api.twitter.com/1.1/statuses/update.json', 'POST'])
            ->andReturnSelf();

        $this->twitterApiExchange
            ->shouldReceive('setPostfields')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $this->twitterApiExchange
            ->shouldReceive('performRequest')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

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
            ->withArgs(['twitter_api_exchange'])
            ->andReturn($this->twitterApiExchange);

        $this->inputInterface = $inputInterface;
        $this->outputInterface = $outputInterface;
        $this->container = $container;
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function testCommandRequiresArgument()
    {
        $this->inputInterface
            ->shouldReceive('getArgument')
            ->once()
            ->withArgs(['message'])
            ->andThrow('\RuntimeException', 'Not enough arguments.');

        $this->runCommand();
    }

    public function testCommandRunsWithMessage()
    {
        $this->inputInterface
            ->shouldReceive('getArgument')
            ->once()
            ->withArgs(['message'])
            ->andReturn('Foo/bar');

        $this->runCommand();
    }

    private function runCommand()
    {
        $command = new TweetCommand();
        $command->setContainer($this->container);
        $command->run($this->inputInterface, $this->outputInterface);
    }
}