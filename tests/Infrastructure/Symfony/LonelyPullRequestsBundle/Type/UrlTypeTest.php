<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\Type;
use LonelyPullRequests\Domain\Url;
use Mockery;
use PHPUnit_Framework_TestCase;

class UrlTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\UrlType
     */
    private $type;

    private $platform;

    public function setUp()
    {
        if(!Type::hasType('url')) {
            Type::addType('url', '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\UrlType');
        }

        $this->type = Type::getType('url');

        $this->platform = Mockery::mock('\Doctrine\DBAL\Platforms\AbstractPlatform');
    }

    public function testName()
    {
        $this->assertEquals('url', $this->type->getName());
        $this->assertEquals('url', (string) $this->type);
    }

    public function testConvertToDatabaseValue()
    {
        $urlName = 'http://www.example.com/';
        $url = Url::fromString($urlName);
        $databaseValue = $this->type->convertToDatabaseValue($url, $this->platform);

        $this->assertTrue(gettype($databaseValue) === 'string');
        $this->assertSame($databaseValue, $urlName);
    }

    public function testConvertToPHPValue()
    {
        $urlName = 'http://www.example.com/';
        $this->assertSame($this->convertToPhp($urlName)->toString(), $urlName);
    }

    private function convertToPhp($value)
    {
        $phpValue = $this->type->convertToPHPValue($value, $this->platform);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Url', $phpValue);
        return $phpValue;
    }
}