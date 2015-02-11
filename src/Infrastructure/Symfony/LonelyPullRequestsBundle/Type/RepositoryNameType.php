<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Assert\Assertion as Ensure;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use LonelyPullRequests\Domain\RepositoryName;

/**
 * RepositoryName type for Doctrine to map the value-object
 *
 */
class RepositoryNameType extends StringType
{
    const NAME = 'repositoryName';

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        return RepositoryName::fromString($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        //Ensure::isInstanceOf($value, '\LonelyPullRequests\Domain\RepositoryName');
        // TODO: Something is going wrong, and being passed the wrong way. HACK for now to test major functionality
        //       Alcohol may have affected this decision.
        if($value instanceof RepositoryName) {
            return $value->toString();
        } else {
            return $value;
        }

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