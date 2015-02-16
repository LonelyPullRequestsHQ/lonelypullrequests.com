<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use LonelyPullRequests\Domain\Loneliness;

/**
 * Loneliness type for Doctrine to map the value-object
 *
 */
class LonelinessType extends Type
{
    const NAME = 'loneliness';

    /**
     * Converts a value from its database representation to its PHP representation
     * of this type.
     *
     * @param mixed                                     $value    The value to convert.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return Loneliness The PHP representation of the value.
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
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * Converts a value from its PHP representation to its database representation
     * of this type.
     *
     * @param Loneliness                                $value    The value to convert.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return int The database representation of the value.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        /** @var \LonelyPullRequests\Domain\Loneliness $value */
        return $value->toInteger();
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingType()
    {
        return \PDO::PARAM_INT;
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