<?php

use DSI\Entity\UserLink;
use DSI\Entity\UserLink_Service;

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

    /** @test */
    public function gettingTheCorrectService()
    {
        $this->userLink = new UserLink();

        $this->checkLinkService('http://facebook.com/', UserLink_Service::Facebook);
        $this->checkLinkService('http://twitter.com/', UserLink_Service::Twitter);
        $this->checkLinkService('http://plus.google.com/', UserLink_Service::GooglePlus);
        $this->checkLinkService('http://github.com/', UserLink_Service::GitHub);
        $this->checkLinkService('http://inoveb.com/', UserLink_Service::Other);
    }

    private function checkLinkService($link, $service)
    {
        $this->userLink->setLink($link);
        $this->assertEquals(
            $service, $this->userLink->getLinkService()
        );
    }
}