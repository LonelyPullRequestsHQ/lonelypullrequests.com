<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\Type;
use LonelyPullRequests\Domain\Summary;
use LonelyPullRequests\Domain\Url;
use Mockery;
use PHPUnit_Framework_TestCase;

class SummaryTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\SummaryType
     */
    private $type;

    private $platform;

    public function setUp()
    {
        if(!Type::hasType('summary')) {
            Type::addType('summary', '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\SummaryType');
        }

        $this->type = Type::getType('summary');

        $this->platform = Mockery::mock('\Doctrine\DBAL\Platforms\AbstractPlatform');
    }

    public function testName()
    {
        $this->assertEquals('summary', $this->type->getName());
        $this->assertEquals('summary', (string) $this->type);
    }

    public function testConvertToDatabaseValue()
    {
        $summaryName = 'summary';
        $summary = Summary::fromString($summaryName);
        $databaseValue = $this->type->convertToDatabaseValue($summary, $this->platform);

        $this->assertTrue(gettype($databaseValue) === 'string');
        $this->assertSame($databaseValue, $summaryName);
    }

    public function testConvertToPHPValue()
    {
        $summary = 'summary';
        $this->assertSame($this->convertToPhp($summary)->toString(), $summary);
    }

    private function convertToPhp($value)
    {
        $phpValue = $this->type->convertToPHPValue($value, $this->platform);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Summary', $phpValue);
        return $phpValue;
    }
}