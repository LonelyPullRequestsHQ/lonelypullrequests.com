<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Controller;

use LonelyPullRequests\Infrastructure\Persistence\InMemoryPullRequestsRepository;
use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    private $container;

    /**
     * @var DefaultController
     */
    private $controller;

    public function setUp()
    {
        $this->container = Mockery::mock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->controller = new DefaultController();
        $this->controller->setContainer($this->container);
    }

    public function testIndexAction()
    {
        $inMemoryRepository = new InMemoryPullRequestsRepository();

        $response = Mockery::mock('\Symfony\Component\HttpFoundation\Response');
        $response
            ->shouldReceive('getStatusCode')
            ->andReturn(200);

        $twig = Mockery::mock('Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine');
        $twig
            ->shouldReceive('renderResponse')
            ->andReturn($response);

        $this->container
            ->shouldReceive('get')
            ->withArgs(['lonely_pull_requests.repository.pull_requests'])
            ->andReturn($inMemoryRepository);

        $this->container
            ->shouldReceive('get')
            ->withArgs(['templating'])
            ->andReturn($twig);

        $response = $this->controller->indexAction();

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}