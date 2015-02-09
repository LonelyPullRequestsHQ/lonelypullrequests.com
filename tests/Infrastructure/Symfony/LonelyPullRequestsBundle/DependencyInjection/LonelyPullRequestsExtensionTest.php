<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\DependencyInjection;

use Mockery;
use PHPUnit_Framework_TestCase;

class LonelyPullRequestsExtensionTest extends PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $containerBuilder = Mockery::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $containerBuilder
            ->shouldReceive('addResource')
            ->andReturn();

        $containerBuilder
            ->shouldReceive('setDefinition')
            ->andReturn();

        $extension = new LonelyPullRequestsExtension();
        $extension->load(array(), $containerBuilder);
    }
}