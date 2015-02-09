<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class SummaryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor should be private
     */
    public function testCannotInstantiateExternally()
    {
        $reflection = new \ReflectionClass('\LonelyPullRequests\Domain\Summary');
        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "" is blank, but was expected to contain a value.
     */
    public function testAssertionOnEmptyString()
    {
        Summary::fromString('');
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "42" expected to be string, type integer given.
     */
    public function testAssertionForStringOnInteger()
    {
        Summary::fromString(42);
    }

    /**
     * Testing toString methods and assert no mutation
     */
    public function testToString()
    {
        $summary = 'foo/bar';

        $summaryObject = Summary::fromString($summary);
        $this->assertEquals($summary, (string) $summaryObject);
        $this->assertEquals($summary, $summaryObject->toString());
    }
}