<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class PullRequest
{
    /**
     * @var Summary
     */
    private $summary;

    /**
     * @var RepositoryName
     */
    private $repositoryName;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var Loneliness
     */
    private $loneliness;

    public static function fromArray(array $array)
    {
        Ensure::keyExists($array, 'summary');
        Ensure::keyExists($array, 'repositoryName');
        Ensure::keyExists($array, 'url');
        Ensure::keyExists($array, 'loneliness');

        return PullRequest::create(
            Summary::fromString($array['summary']),
            RepositoryName::fromString($array['repositoryName']),
            Url::fromString($array['url']),
            Loneliness::fromInteger($array['loneliness'])
        );
    }

    public static function create(Summary $summary, RepositoryName $repositoryName, Url $url, Loneliness $loneliness)
    {
        return new PullRequest($summary, $repositoryName, $url, $loneliness);
    }

    private  function __construct(Summary $summary, RepositoryName $repositoryName, Url $url, Loneliness $loneliness)
    {
        $this->summary = $summary;
        $this->repositoryName = $repositoryName;
        $this->url = $url;
        $this->loneliness = $loneliness;
    }

    /**
     * @return Summary
     */
    public function summary()
    {
        return $this->summary;
    }

    /**
     * @return RepositoryName
     */
    public function repositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * @return Url
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @return Loneliness
     */
    public function loneliness()
    {
        return $this->loneliness;
    }
}
