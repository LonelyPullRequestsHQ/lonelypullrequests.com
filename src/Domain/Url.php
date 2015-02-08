<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class Url
{
    /**
     * @var string
     */
    private $url;

    public static function fromString($string)
    {
        return new Url($string);
    }

    private function __construct($url)
    {
        Ensure::url($url);

        $this->url = $url;
    }

    public function toString()
    {
        return $this->url;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
