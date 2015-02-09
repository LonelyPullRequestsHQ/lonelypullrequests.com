<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class RepositoryNameTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor should be private
     */
    public function testCannotInstantiateExternally()
    {
        $reflection = new \ReflectionClass('\LonelyPullRequests\Domain\RepositoryName');
        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "" is blank, but was expected to contain a value.
     */
    public function testAssertionOnEmptyString()
    {
        RepositoryName::fromString('');
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "42" expected to be string, type integer given.
     */
    public function testAssertionForStringOnInteger()
    {
        RepositoryName::fromString(42);
    }

    /**
     * Testing toString methods and assert no mutation
     */
    public function testToString()
    {
        $repositoryName = 'foo/bar';

        $repositoryNameObject = RepositoryName::fromString($repositoryName);
        $this->assertEquals($repositoryName, (string) $repositoryNameObject);
        $this->assertEquals($repositoryName, $repositoryNameObject->toString());
    }
}