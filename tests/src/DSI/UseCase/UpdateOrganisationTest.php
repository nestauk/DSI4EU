<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\OrganisationRepo;

class UpdateOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateOrganisation */
    private $updateOrganisation;

    /** @var OrganisationRepo */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Entity\User */
    private $user1, $user2;

    public function setUp()
    {
        $this->updateOrganisation = new \DSI\UseCase\UpdateOrganisation();
        $this->organisationRepo = new OrganisationRepo();

        $this->user1 = new \DSI\Entity\User();
        $userRepo = new \DSI\Repository\UserRepo();
        $userRepo->insert($this->user1);

        $this->user2 = new \DSI\Entity\User();
        $userRepo = new \DSI\Repository\UserRepo();
        $userRepo->insert($this->user2);

        $createOrganisation = new \DSI\UseCase\CreateOrganisation();
        $createOrganisation->data()->name = 'Organisation Name';
        $createOrganisation->data()->owner = $this->user1;
        $createOrganisation->forceCreation = true;
        $createOrganisation->exec();

        $this->organisation = $createOrganisation->getOrganisation();
    }

    public function tearDown()
    {
        $this->organisationRepo->clearAll();
        (new \DSI\Repository\OrganisationMemberRepo())->clearAll();
        (new \DSI\Repository\ContentUpdateRepo())->clearAll();
    }

    /** @test */
    public function successfulUpdate()
    {
        $name = 'Name';
        $description = 'Description';

        $this->updateOrganisation->data()->name = $name;
        $this->updateOrganisation->data()->description = $description;
        $this->updateOrganisation->data()->organisation = $this->organisation;
        $this->updateOrganisation->data()->executor = $this->user1;

        $e = null;
        try {
            $this->updateOrganisation->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNull($e);
        $organisation = $this->organisationRepo->getById($this->organisation->getId());
        $this->assertEquals($name, $organisation->getName());
        $this->assertEquals($description, $organisation->getDescription());
        $this->assertEquals($this->user1->getId(), $organisation->getOwnerID());
    }

    /** @test */
    public function changingNameCreatesContentUpdate()
    {
        $this->deleteFirstContentUpdate();

        $this->updateOrganisation->data()->name = 'New name';
        $this->updateOrganisation->data()->organisation = $this->organisation;
        $this->updateOrganisation->data()->executor = $this->user1;

        $e = null;
        try {
            $this->updateOrganisation->exec();
        } catch (ErrorHandler $e) {
        }

        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $contentUpdates);

        $contentUpdate = $contentUpdates[0];

        $this->assertEquals($this->organisation->getId(), $contentUpdate->getOrganisationID());
        $this->assertEquals(\DSI\Entity\ContentUpdate::Updated_Content, $contentUpdate->getUpdated());
    }

    /** @test */
    public function changingDescCreatesContentUpdate()
    {
        $this->deleteFirstContentUpdate();

        $this->updateOrganisation->data()->description = 'New description';
        $this->updateOrganisation->data()->organisation = $this->organisation;
        $this->updateOrganisation->data()->executor = $this->user1;

        $e = null;
        try {
            $this->updateOrganisation->exec();
        } catch (ErrorHandler $e) {
        }

        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $contentUpdates);

        $contentUpdate = $contentUpdates[0];

        $this->assertEquals($this->organisation->getId(), $contentUpdate->getOrganisationID());
        $this->assertEquals(\DSI\Entity\ContentUpdate::Updated_Content, $contentUpdate->getUpdated());
    }

    /** @test */
    public function givenAContentUpdateExists_whenOrganisationIsUpdated_thenTheExistingContentUpdateIsUpdated()
    {
        $this->updateOrganisation->data()->name = 'New name';
        $this->updateOrganisation->data()->organisation = $this->organisation;
        $this->updateOrganisation->data()->executor = $this->user1;

        $e = null;
        try {
            $this->updateOrganisation->exec();
        } catch (ErrorHandler $e) {
        }

        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $contentUpdates);

        $contentUpdate = $contentUpdates[0];

        $this->assertEquals($this->organisation->getId(), $contentUpdate->getOrganisationID());
        $this->assertEquals(\DSI\Entity\ContentUpdate::New_Content, $contentUpdate->getUpdated());
    }


    private function deleteFirstContentUpdate()
    {
        $contentUpdate = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        (new \DSI\UseCase\ContentUpdates\RemoveContentUpdate())
            ->setContentUpdate($contentUpdate[0])
            ->exec();
    }
}