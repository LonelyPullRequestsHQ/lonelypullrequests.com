<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class Summary
{
    /**
     * @var string
     */
    private $summary;

    public static function fromString($string)
    {
        return new Summary($string);
    }

    private function __construct($summary)
    {
        Ensure::string($summary);
        Ensure::notBlank($summary);

        $this->summary = $summary;
    }

    public function toString()
    {
        return $this->summary;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
