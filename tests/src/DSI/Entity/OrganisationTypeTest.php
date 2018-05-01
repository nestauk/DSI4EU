<?php

use DSI\Entity\OrganisationType;

require_once __DIR__ . '/../../../config.php';

class OrganisationTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var OrganisationType */
    private $organisationType;

    public function setUp()
    {
        $this->organisationType = new OrganisationType();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->organisationType->setId(1);
        $this->assertEquals(1, $this->organisationType->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->organisationType->setId(0);
    }

    /** @test setName, getName */
    public function settingName_returnsName()
    {
        $name = 'Brand New Organisation';
        $this->organisationType->setName($name);
        $this->assertEquals($name, $this->organisationType->getName());
    }

    /** @test */
    public function settingOrder_returnsOrder()
    {
        $order = 10;
        $this->organisationType->setOrder($order);
        $this->assertEquals($order, $this->organisationType->getOrder());
    }
}