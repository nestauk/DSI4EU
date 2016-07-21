<?php

use DSI\Entity\OrganisationLink;

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

        $this->organisationLink= new OrganisationLink();
        $this->organisationLink->setOrganisation($organisation);
        $this->organisationLink->setLink($url);

        $this->assertEquals($organisation->getId(), $this->organisationLink->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationLink->getOrganisation()->getId());
        $this->assertEquals($url, $this->organisationLink->getLink());
    }
}