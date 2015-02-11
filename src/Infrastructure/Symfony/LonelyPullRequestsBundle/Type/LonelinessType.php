<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use LonelyPullRequests\Domain\Loneliness;

/**
 * Loneliness type for Doctrine to map the value-object
 *
 */
class LonelinessType extends IntegerType
{
    const NAME = 'loneliness';

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        if(ctype_digit($value)) {
            $value = (int) $value;
        }

        return Loneliness::fromInteger($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        /** @var \LonelyPullRequests\Domain\Loneliness $value */
        return $value->toInteger();
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return self::NAME;
    }
}