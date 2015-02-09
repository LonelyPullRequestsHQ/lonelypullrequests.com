<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\DependencyInjection;

use PHPUnit_Framework_TestCase;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testConfigTreeBuilder()
    {
        $configuration = new Configuration();
        $treeBuilder = $configuration->getConfigTreeBuilder();
        $this->assertInstanceOf('\Symfony\Component\Config\Definition\Builder\TreeBuilder', $treeBuilder);
    }
}