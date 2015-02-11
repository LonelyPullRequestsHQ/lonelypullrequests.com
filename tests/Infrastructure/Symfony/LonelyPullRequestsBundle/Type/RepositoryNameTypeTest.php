<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\Type;
use LonelyPullRequests\Domain\RepositoryName;
use Mockery;
use PHPUnit_Framework_TestCase;

class RepositoryNameTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\RepositoryNameType
     */
    private $type;

    private $platform;

    public function setUp()
    {
        if(!Type::hasType('repositoryName')) {
            Type::addType('repositoryName', '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\RepositoryNameType');
        }

        $this->type = Type::getType('repositoryName');

        $this->platform = Mockery::mock('\Doctrine\DBAL\Platforms\AbstractPlatform');
    }

    public function testName()
    {
        $this->assertEquals('repositoryName', $this->type->getName());
        $this->assertEquals('repositoryName', (string) $this->type);
    }

    public function testConvertToDatabaseValue()
    {
        $repositoryNameName = 'foo/bar';
        $repositoryName = RepositoryName::fromString($repositoryNameName);
        $databaseValue = $this->type->convertToDatabaseValue($repositoryName, $this->platform);

        $this->assertTrue(gettype($databaseValue) === 'string');
        $this->assertSame($databaseValue, $repositoryNameName);

        $databaseValue = $this->type->convertToDatabaseValue($repositoryNameName, $this->platform);
        $this->assertTrue(gettype($databaseValue) === 'string');
        $this->assertSame($databaseValue, $repositoryNameName);

    }

    public function testConvertToPHPValue()
    {
        $repositoryNameName = 'foo/bar';
        $this->assertSame($this->convertToPhp($repositoryNameName)->toString(), $repositoryNameName);
    }

    private function convertToPhp($value)
    {
        $phpValue = $this->type->convertToPHPValue($value, $this->platform);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\RepositoryName', $phpValue);
        return $phpValue;
    }
}