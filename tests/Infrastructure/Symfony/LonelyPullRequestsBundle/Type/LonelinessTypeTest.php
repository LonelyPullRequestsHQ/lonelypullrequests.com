<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\Type;
use LonelyPullRequests\Domain\Loneliness;
use Mockery;
use PHPUnit_Framework_TestCase;

class LonelinessTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\LonelinessType
     */
    private $type;

    private $platform;

    public function setUp()
    {
        if(!Type::hasType('loneliness')) {
            Type::addType('loneliness', '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\LonelinessType');
        }

        $this->type = Type::getType('loneliness');

        $this->platform = Mockery::mock('\Doctrine\DBAL\Platforms\AbstractPlatform');
    }

    public function testName()
    {
        $this->assertEquals('loneliness', $this->type->getName());
        $this->assertEquals('loneliness', (string) $this->type);
    }

    public function testBindingType()
    {
        $this->assertEquals(\PDO::PARAM_INT, $this->type->getBindingType());
    }

    public function testSqlDeclaration()
    {
        $fieldDeclaration = [
            'foo' => 'bar',
        ];

        $this->platform
            ->shouldReceive('getIntegerTypeDeclarationSQL')
            ->withArgs([$fieldDeclaration]);

        $this->type->getSQLDeclaration($fieldDeclaration, $this->platform);
    }

    public function testConvertToDatabaseValue()
    {
        $lonelinessScore = 42;
        $loneliness = Loneliness::fromInteger($lonelinessScore);
        $databaseValue = $this->type->convertToDatabaseValue($loneliness, $this->platform);

        $this->assertTrue(gettype($databaseValue) === 'integer');
        $this->assertSame($databaseValue, $lonelinessScore);
    }

    public function testConvertToPHPValue()
    {
        $lonelinessScore = 42;
        $this->assertSame($this->convertToPhp($lonelinessScore)->toInteger(), $lonelinessScore);
    }

    public function testConvertToPHPValueFromString()
    {
        $lonelinessScore = '42';
        $this->assertSame($this->convertToPhp($lonelinessScore)->toString(), $lonelinessScore);
    }

    private function convertToPhp($value)
    {
        $phpValue = $this->type->convertToPHPValue($value, $this->platform);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Loneliness', $phpValue);
        return $phpValue;
    }
}