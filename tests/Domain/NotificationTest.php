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
            'eventDateTime' => $dateTime
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
}