<?php

namespace LonelyPullRequests\Domain;

use PHPUnit_Framework_TestCase;

class NotificationsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Class "foo" was expected to be instanceof of "LonelyPullRequests\Domain\Notification" but is not.
     */
    public function testArrayContainsPullRequestObjects()
    {
        new Notifications(array(
            'foo',
            'bar',
            42,
        ));
    }

    public function testEmptyArray()
    {
        $notifications = new Notifications(array());
        $this->assertEmpty($notifications);
    }

    public function testImmutability()
    {
        $notifications = new Notifications();
        $notification = Notification::fromArray([
            'repositoryName' => 'foo/bar',
            'title' => 'Title',
            'url' => 'http://www.example.com/',
            'eventDateTime' => strftime("%Y-%m-%d %H:%M:%S", time()),
        ]);

        $newNotifications = $notifications->add($notification);
        $anotherNotifications = $notifications->add($notification);

        $this->assertNotSame($notifications, $newNotifications);
        $this->assertNotSame($notifications, $anotherNotifications);
        $this->assertNotSame($newNotifications, $anotherNotifications);
        $this->assertEquals($newNotifications, $anotherNotifications);
    }

    public function testIterator()
    {
        $notifications = new Notifications();
        $iterator = $notifications->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);
    }
}