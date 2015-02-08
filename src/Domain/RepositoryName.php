<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class RepositoryName
{
    /**
     * @var string
     */
    private $repositoryName;

    public static function fromString($string)
    {
        return new RepositoryName($string);
    }

    private function __construct($repositoryName)
    {
        Ensure::string($repositoryName);
        Ensure::notBlank($repositoryName);
        Ensure::contains($repositoryName, '/');

        $this->repositoryName = $repositoryName;
    }

    public function toString()
    {
        return $this->repositoryName;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
