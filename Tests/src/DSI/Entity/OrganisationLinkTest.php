<?php

use \DSI\Entity\OrganisationLink;
use \DSI\Entity\OrganisationLink_Service;

require_once __DIR__ . '/../../../config.php';

class OrganisationLinkTest extends \PHPUnit_Framework_TestCase
{
    /** @var OrganisationLink */
    private $organisationLink;

    public function setUp()
    {
        $this->organisationLink = new OrganisationLink();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);

        $url = 'http://example.com';

        $this->organisationLink = new OrganisationLink();
        $this->organisationLink->setOrganisation($organisation);
        $this->organisationLink->setLink($url);

        $this->assertEquals($organisation->getId(), $this->organisationLink->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationLink->getOrganisation()->getId());
        $this->assertEquals($url, $this->organisationLink->getLink());
    }

    /** @test */
    public function gettingTheCorrectService()
    {
        $this->organisationLink = new OrganisationLink();

        $this->checkLinkService('http://facebook.com/', OrganisationLink_Service::Facebook);
        $this->checkLinkService('http://twitter.com/', OrganisationLink_Service::Twitter);
        $this->checkLinkService('http://plus.google.com/', OrganisationLink_Service::GooglePlus);
        $this->checkLinkService('http://github.com/', OrganisationLink_Service::GitHub);
        $this->checkLinkService('http://inoveb.com/', OrganisationLink_Service::Other);
    }

    private function checkLinkService($link, $service)
    {
        $this->organisationLink->setLink($link);
        $this->assertEquals(
            $service, $this->organisationLink->getLinkService()
        );
    }
}