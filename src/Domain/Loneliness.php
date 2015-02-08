<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class Loneliness
{
    /**
     * @var integer
     */
    private $score;

    /**
     * @param integer $score
     * @return Loneliness
     */
    public static function fromInteger($score)
    {
        return new Loneliness($score);
    }

    /**
     * @param integer $score
     */
    private function __construct($score)
    {
        Ensure::integer($score);

        $this->score = $score;
    }

    public function toInteger()
    {
        return $this->score;
    }

    public function toString()
    {
        return (string) $this->score;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
