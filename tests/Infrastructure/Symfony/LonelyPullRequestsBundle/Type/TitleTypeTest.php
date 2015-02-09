<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\Type;
use LonelyPullRequests\Domain\Title;
use Mockery;
use PHPUnit_Framework_TestCase;

class TitleTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\TitleType
     */
    private $type;

    private $platform;

    public function setUp()
    {
        if(!Type::hasType('title')) {
            Type::addType('title', '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\TitleType');
        }

        $this->type = Type::getType('title');

        $this->platform = Mockery::mock('\Doctrine\DBAL\Platforms\AbstractPlatform');
    }

    public function testName()
    {
        $this->assertEquals('title', $this->type->getName());
        $this->assertEquals('title', (string) $this->type);
    }

    public function testConvertToDatabaseValue()
    {
        $titleName = 'title';
        $title = Title::fromString($titleName);
        $databaseValue = $this->type->convertToDatabaseValue($title, $this->platform);

        $this->assertTrue(gettype($databaseValue) === 'string');
        $this->assertSame($databaseValue, $titleName);
    }

    public function testConvertToPHPValue()
    {
        $title = 'title';
        $this->assertSame($this->convertToPhp($title)->toString(), $title);
    }

    private function convertToPhp($value)
    {
        $phpValue = $this->type->convertToPHPValue($value, $this->platform);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Title', $phpValue);
        return $phpValue;
    }
}