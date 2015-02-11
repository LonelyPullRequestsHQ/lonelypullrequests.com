<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class LonelinessTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor should be private
     */
    public function testCannotInstantiateExternally()
    {
        $reflection = new \ReflectionClass('\LonelyPullRequests\Domain\Loneliness');
        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "foo" is not an integer.
     */
    public function testAssertionOnString()
    {
        Loneliness::fromInteger('foo');
    }

    /**
     * Testing toString methods and assert no mutation
     */
    public function testToString()
    {
        $lonelinessScore = 42;

        $loneliness = Loneliness::fromInteger($lonelinessScore);

        // String casting
        $stringCastedLoneliness = (string) $loneliness;
        $this->assertEquals($lonelinessScore, $stringCastedLoneliness);
        $this->assertTrue(gettype($stringCastedLoneliness) === 'string');

        // String converted
        $stringConvertedLoneliness = $loneliness->toString();
        $this->assertEquals($lonelinessScore, $stringConvertedLoneliness);
        $this->assertTrue(gettype($stringConvertedLoneliness) === 'string');

        // String converted
        $integerConvertedLoneliness = $loneliness->toInteger();
        $this->assertEquals($lonelinessScore, $integerConvertedLoneliness);
        $this->assertTrue(gettype($integerConvertedLoneliness) === 'integer');
    }
}