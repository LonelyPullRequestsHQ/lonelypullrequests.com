<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class UrlTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor should be private
     */
    public function testCannotInstantiateExternally()
    {
        $reflection = new \ReflectionClass('\LonelyPullRequests\Domain\Url');
        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Value "" was expected to be a valid URL starting with http or https
     */
    public function testAssertionOnEmptyString()
    {
        Url::fromString('');
    }

    /**
     * Testing toString methods and assert no mutation and a valid URL
     */
    public function testToString()
    {
        $url = 'https://www.lonelypullrequests.com/';

        $urlObject = Url::fromString($url);
        $this->assertEquals($urlObject, (string) $urlObject);
        $this->assertEquals($urlObject, $urlObject->toString());
    }
}