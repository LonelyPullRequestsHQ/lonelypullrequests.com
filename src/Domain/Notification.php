<?php

namespace LonelyPullRequests\Domain;

final class Notification
{
    /**
     * @param $array
     *
     * @return Notification
     */
    public static function fromArray($array)
    {
        return new self();
    }

    /**
     *
     */
    private function __construct()
    {

    }
}
