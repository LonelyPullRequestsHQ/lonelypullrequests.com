<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class NotificationTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor should be private
     */
    public function testCannotInstantiateExternally()
    {
        $reflection = new \ReflectionClass('\LonelyPullRequests\Domain\Notification');
        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Array does not contain an element with key "repositoryName"
     */
    public function testFailingCreationOnEmptyArray()
    {
        Notification::fromArray(array());
    }

    public function testInstantiationWithGetters()
    {
        $title = 'foobar';
        $repositoryName = 'foo/bar';
        $url = 'https://www.lonelypullrequests.com/';
        $dateTime = 'now';

        $notification = Notification::fromArray(array(
            'title' => $title,
            'repositoryName' => $repositoryName,
            'url' => $url,
            'eventDateTime' => $dateTime,
            'pullRequestState' => PullRequestState::STATE_OPEN
        ));

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Notification', $notification);

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Title', $notification->title());
        $this->assertEquals($title, $notification->title()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\RepositoryName', $notification->repositoryName());
        $this->assertEquals($repositoryName, $notification->repositoryName()->toString());

        $this->assertInstanceOf('\LonelyPullRequests\Domain\Url', $notification->url());
        $this->assertEquals($url, $notification->url()->toString());

        $this->assertInstanceOf('\DateTimeInterface', $notification->eventDateTime());
    }

    public function testConvertionToPullRequest()
    {
        $title = 'foobar';
        $repositoryName = 'foo/bar';
        $url = 'https://www.lonelypullrequests.com/';
        $dateTime = 'now';
        $lonelinessScore = 31337;
        $loneliness = Loneliness::fromInteger($lonelinessScore);

        $notification = Notification::fromArray(array(
            'title' => $title,
            'repositoryName' => $repositoryName,
            'url' => $url,
            'eventDateTime' => $dateTime,
            'pullRequestState' => PullRequestState::STATE_OPEN
        ));

        $pullRequest = $notification->pullRequest($loneliness);

        $this->assertSame($title, $pullRequest->title()->toString());
        $this->assertSame($repositoryName, $pullRequest->repositoryName()->toString());
        $this->assertSame($url, $pullRequest->url()->toString());
        $this->assertSame($lonelinessScore, $pullRequest->loneliness()->toInteger());
        $this->assertSame(PullRequestState::STATE_OPEN, (string) $notification->pullRequestState());
    }

}