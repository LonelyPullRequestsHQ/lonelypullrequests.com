<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class Title
{
    /**
     * @var string
     */
    private $title;

    public static function fromString($string)
    {
        return new Title($string);
    }

    private function __construct($title)
    {
        Ensure::string($title);
        Ensure::notBlank($title);

        $this->title = $title;
    }

    public function toString()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
