<?php

use DSI\Entity\OrganisationSize;

require_once __DIR__ . '/../../../config.php';

class OrganisationSizeTest extends \PHPUnit_Framework_TestCase
{
    /** @var OrganisationSize */
    private $organisationSize;

    public function setUp()
    {
        $this->organisationSize = new OrganisationSize();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->organisationSize->setId(1);
        $this->assertEquals(1, $this->organisationSize->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->organisationSize->setId(0);
    }

    /** @test setName, getName */
    public function settingName_returnsName()
    {
        $name = 'Brand New Organisation';
        $this->organisationSize->setName($name);
        $this->assertEquals($name, $this->organisationSize->getName());
    }

    /** @test */
    public function settingOrder_returnsOrder()
    {
        $order = 9;
        $this->organisationSize->setOrder($order);
        $this->assertEquals($order, $this->organisationSize->getOrder());
    }
}