<?php

namespace LonelyPullRequests\Domain;

use Assert\Assertion as Ensure;

final class PullRequest
{
    /**
     * @var Title
     */
    private $title;

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
        Ensure::keyExists($array, 'title');
        Ensure::keyExists($array, 'repositoryName');
        Ensure::keyExists($array, 'url');
        Ensure::keyExists($array, 'loneliness');

        return PullRequest::create(
            Title::fromString($array['title']),
            RepositoryName::fromString($array['repositoryName']),
            Url::fromString($array['url']),
            Loneliness::fromInteger($array['loneliness'])
        );
    }

    public static function create(Title $title, RepositoryName $repositoryName, Url $url, Loneliness $loneliness)
    {
        return new PullRequest($title, $repositoryName, $url, $loneliness);
    }

    private  function __construct(Title $title, RepositoryName $repositoryName, Url $url, Loneliness $loneliness)
    {
        $this->title = $title;
        $this->repositoryName = $repositoryName;
        $this->url = $url;
        $this->loneliness = $loneliness;
    }

    /**
     * @return Title
     */
    public function title()
    {
        return $this->title;
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
