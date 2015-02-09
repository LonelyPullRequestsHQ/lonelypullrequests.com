<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use LonelyPullRequests\Domain\Summary;

/**
 * Summary type for Doctrine to map the value-object
 *
 */
class SummaryType extends StringType
{
    const NAME = 'summary';

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        return Summary::fromString($value);
    }

    /**
     * {@inheritdoc}
     */

    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        return $value->toString();
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