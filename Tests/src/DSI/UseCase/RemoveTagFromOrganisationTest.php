<?php

require_once __DIR__ . '/../../../config.php';

class RemoveTagFromOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddTagToOrganisation */
    private $addTagToOrganisationCommand;

    /** @var \DSI\UseCase\RemoveTagFromOrganisation */
    private $removeTagFromOrganisationCommand;

    /** @var \DSI\Repository\TagForOrganisationsRepository*/
    private $tagRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Repository\OrganisationTagRepository */
    private $organisationTagRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    public function setUp()
    {
        $this->addTagToOrganisationCommand = new \DSI\UseCase\AddTagToOrganisation();
        $this->removeTagFromOrganisationCommand = new \DSI\UseCase\RemoveTagFromOrganisation();
        $this->organisationTagRepo = new \DSI\Repository\OrganisationTagRepository();
        $this->tagRepo = new \DSI\Repository\TagForOrganisationsRepository();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

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