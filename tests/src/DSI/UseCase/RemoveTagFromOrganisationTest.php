<?php

require_once __DIR__ . '/../../../config.php';

class RemoveTagFromOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddTagToOrganisation */
    private $addTagToOrganisationCommand;

    /** @var \DSI\UseCase\RemoveTagFromOrganisation */
    private $removeTagFromOrganisationCommand;

    /** @var \DSI\Repository\TagForOrganisationsRepo*/
    private $tagRepo;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Repository\OrganisationTagRepo */
    private $organisationTagRepo;

    /** @var \DSI\Repository\OrganisationRepo */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    public function setUp()
    {
        $this->addTagToOrganisationCommand = new \DSI\UseCase\AddTagToOrganisation();
        $this->removeTagFromOrganisationCommand = new \DSI\UseCase\RemoveTagFromOrganisation();
        $this->organisationTagRepo = new \DSI\Repository\OrganisationTagRepo();
        $this->tagRepo = new \DSI\Repository\TagForOrganisationsRepo();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $user = new \DSI\Entity\User();
        $this->userRepo->insert($user);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($user);
        $this->organisationRepo->insert($this->organisation);
    }

    public function tearDown()
    {
        $this->tagRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
        $this->organisationTagRepo->clearAll();
    }

    /** @test */
    public function successfulRemoveTagFromOrganisation()
    {
        $this->addTagToOrganisationCommand->data()->tag = 'test';
        $this->addTagToOrganisationCommand->data()->organisationID = $this->organisation->getId();
        $this->addTagToOrganisationCommand->exec();

        $this->removeTagFromOrganisationCommand->data()->tag = 'test';
        $this->removeTagFromOrganisationCommand->data()->organisationID = $this->organisation->getId();
        $this->removeTagFromOrganisationCommand->exec();

        $this->assertFalse(
            $this->organisationTagRepo->organisationHasTagName(
                $this->removeTagFromOrganisationCommand->data()->organisationID,
                $this->removeTagFromOrganisationCommand->data()->tag
            )
        );
    }

    /** @test */
    public function cannotRemoveIfUserDoesNotHaveIt()
    {
        $e = null;

        try {
            $this->removeTagFromOrganisationCommand->data()->tag = 'test';
            $this->removeTagFromOrganisationCommand->data()->organisationID = $this->organisation->getId();
            $this->removeTagFromOrganisationCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('tag'));
    }
}