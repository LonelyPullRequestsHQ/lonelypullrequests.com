<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class TitleTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor should be private
     */
    public function testCannotInstantiateExternally()
    {
        $reflection = new \ReflectionClass('\LonelyPullRequests\Domain\Title');
        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "" is blank, but was expected to contain a value.
     */
    public function testAssertionOnEmptyString()
    {
        Title::fromString('');
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "42" expected to be string, type integer given.
     */
    public function testAssertionForStringOnInteger()
    {
        Title::fromString(42);
    }

    /**
     * Testing toString methods and assert no mutation
     */
    public function testToString()
    {
        $title = 'foo/bar';

        $titleObject = Title::fromString($title);
        $this->assertEquals($title, (string) $titleObject);
        $this->assertEquals($title, $titleObject->toString());
    }
}