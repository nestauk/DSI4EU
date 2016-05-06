<?php

use DSI\Entity\UserLink;

require_once __DIR__ . '/../../../config.php';

class UserLinkTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserLink */
    private $userLink;

    public function setUp()
    {
        $this->userLink = new UserLink();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $user = new \DSI\Entity\User();
        $user->setId(1);

        $url = 'http://example.com';

        $this->userLink= new UserLink();
        $this->userLink->setUser($user);
        $this->userLink->setLink($url);

        $this->assertEquals($user->getId(), $this->userLink->getUserID());
        $this->assertEquals($user->getId(), $this->userLink->getUser()->getId());
        $this->assertEquals($url, $this->userLink->getLink());
    }
}