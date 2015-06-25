<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use LonelyPullRequests\Domain\Url;

/**
 * Url type for Doctrine to map the value-object
 *
 */
class UrlType extends StringType
{
    const NAME = 'url';

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Url::fromString($value);
    }

    /**
     * {@inheritdoc}
     */

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
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
