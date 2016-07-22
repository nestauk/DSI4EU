<?php

require_once __DIR__ . '/../../../config.php';

class ProjectLinkRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectLinkRepository */
    private $projectLinkRepository;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepository;

    /** @var \DSI\Entity\Project */
    private $project_1, $project_2, $project_3;

    /** @var string */
    private $link_1, $link_2, $link_3;

    /** @var  \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->projectLinkRepository = new \DSI\Repository\ProjectLinkRepository();
        $this->projectRepository = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->project_1 = $this->createProject(1);
        $this->project_2 = $this->createProject(2);
        $this->project_3 = $this->createProject(3);
        $this->link_1 = 'http://example.com/';
        $this->link_2 = 'http://google.com/';
        $this->link_3 = 'http://yahoo.com/';
    }

    public function tearDown()
    {
        $this->projectLinkRepository->clearAll();
        $this->projectRepository->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectLinkCanBeSaved()
    {
        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_1);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_2);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_2);
        $projectLink->setLink($this->link_1);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_3);
        $projectLink->setLink($this->link_1);
        $this->projectLinkRepository->add($projectLink);

        $this->assertCount(2, $this->projectLinkRepository->getByProjectID(1));
    }

    /** @test */
    public function cannotAddSameProjectLinkTwice()
    {
        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_1);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->projectLinkRepository->add($projectLink);
    }

    /** @test saveAsNew */
    public function canCheckIfProjectHasLink()
    {
        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_1);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_2);
        $projectLink->setLink($this->link_2);
        $this->projectLinkRepository->add($projectLink);

        $this->assertTrue($this->projectLinkRepository->projectHasLink(
            $this->project_1->getId(), $this->link_1)
        );
        $this->assertFalse($this->projectLinkRepository->projectHasLink(
            $this->project_1->getId(), $this->link_2)
        );
        $this->assertTrue($this->projectLinkRepository->projectHasLink(
            $this->project_2->getId(), $this->link_2)
        );
        $this->assertFalse($this->projectLinkRepository->projectHasLink(
            $this->project_2->getId(), $this->link_1)
        );
    }

    /** @test saveAsNew */
    public function canGetLinksByProjectID()
    {
        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_1);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_2);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_2);
        $projectLink->setLink($this->link_3);
        $this->projectLinkRepository->add($projectLink);

        $this->assertEquals(
            [$this->link_1, $this->link_2],
            $this->projectLinkRepository->getLinksByProjectID($this->project_1->getId())
        );
        $this->assertEquals(
            [$this->link_3],
            $this->projectLinkRepository->getLinksByProjectID($this->project_2->getId())
        );
    }

    /** @test remove */
    public function canRemoveLinkFromProject()
    {
        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_1);
        $this->projectLinkRepository->add($projectLink);

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_2);
        $this->projectLinkRepository->add($projectLink);

        $this->assertEquals(
            [$this->link_1, $this->link_2],
            $this->projectLinkRepository->getLinksByProjectID($this->project_1->getId())
        );

        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_2);
        $this->projectLinkRepository->remove($projectLink);

        $this->assertEquals(
            [$this->link_1],
            $this->projectLinkRepository->getLinksByProjectID($this->project_1->getId())
        );
    }

    /** @test remove */
    public function cannotRemoveNonexistentLinkFromProject()
    {
        $projectLink = new \DSI\Entity\ProjectLink();
        $projectLink->setProject($this->project_1);
        $projectLink->setLink($this->link_2);
        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectLinkRepository->remove($projectLink);
    }

    private function createProject(int $projectID)
    {
        $project = new \DSI\Entity\Project();
        $project->setId($projectID);
        $project->setOwner($this->user);
        $this->projectRepository->insert($project);
        return $project;
    }
}