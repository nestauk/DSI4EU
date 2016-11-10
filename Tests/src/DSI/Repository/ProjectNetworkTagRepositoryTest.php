<?php

require_once __DIR__ . '/../../../config.php';

class ProjectNetworkTagRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectNetworkTagRepository */
    protected $projectNetworkTagRepo;

    /** @var \DSI\Repository\ProjectRepository */
    protected $projectsRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $usersRepo;

    /** @var \DSI\Entity\Project */
    protected $project_1, $project_2, $project_3;

    /** @var \DSI\Repository\NetworkTagRepository */
    protected $networkTagRepo;

    /** @var \DSI\Entity\NetworkTag */
    protected $tag_1, $tag_2, $tag_3;

    public function setUp()
    {
        $this->projectNetworkTagRepo = new \DSI\Repository\ProjectNetworkTagRepository();
        $this->projectsRepo = new \DSI\Repository\ProjectRepository();
        $this->networkTagRepo = new \DSI\Repository\NetworkTagRepository();
        $this->usersRepo = new \DSI\Repository\UserRepository();

        $this->project_1 = $this->createProject(1);
        $this->project_2 = $this->createProject(2);
        $this->project_3 = $this->createProject(3);
        $this->tag_1 = $this->createTag(1);
        $this->tag_2 = $this->createTag(2);
        $this->tag_3 = $this->createTag(3);
    }

    public function tearDown()
    {
        $this->projectNetworkTagRepo->clearAll();
        $this->projectsRepo->clearAll();
        $this->networkTagRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectTagCanBeSaved()
    {
        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_2);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_3);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $this->assertCount(2, $this->projectNetworkTagRepo->getByProjectID(1));
    }

    /** @test saveAsNew */
    public function cannotAddSameProjectTagTwice()
    {
        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->projectNetworkTagRepo->add($projectTag);
    }

    /** @test saveAsNew */
    public function getAllTagIDsForProject()
    {
        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_2);
        $this->projectNetworkTagRepo->add($projectTag);

        $this->assertEquals([1, 2], $this->projectNetworkTagRepo->getTagIDsForProject(1));
    }

    /** @test saveAsNew */
    public function getAllProjectsForTag()
    {
        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $this->assertCount(2, $this->projectNetworkTagRepo->getByTagID(1));
    }

    /** @test saveAsNew */
    public function getAllProjectIDsForTag()
    {
        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $this->assertEquals([1, 2], $this->projectNetworkTagRepo->getProjectIDsForTag(1));
    }

    /** @test saveAsNew */
    public function canCheckIfProjectHasTagName()
    {
        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_2);
        $this->projectNetworkTagRepo->add($projectTag);

        $this->assertTrue($this->projectNetworkTagRepo->projectHasTagName(
            $this->project_1->getId(), $this->tag_1->getName())
        );
        $this->assertFalse($this->projectNetworkTagRepo->projectHasTagName(
            $this->project_1->getId(), $this->tag_2->getName())
        );
        $this->assertTrue($this->projectNetworkTagRepo->projectHasTagName(
            $this->project_2->getId(), $this->tag_2->getName())
        );
        $this->assertFalse($this->projectNetworkTagRepo->projectHasTagName(
            $this->project_2->getId(), $this->tag_1->getName())
        );
    }

    /** @test saveAsNew */
    public function canGetTagNamesByProjectID()
    {
        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_2);
        $this->projectNetworkTagRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectNetworkTag();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_3);
        $this->projectNetworkTagRepo->add($projectTag);

        $this->assertEquals(
            [$this->tag_1->getName(), $this->tag_2->getName()],
            $this->projectNetworkTagRepo->getTagNamesByProject($this->project_1)
        );
        $this->assertEquals(
            [$this->tag_3->getName()],
            $this->projectNetworkTagRepo->getTagNamesByProject($this->project_2)
        );
    }


    private function createProject(int $projectID)
    {
        $user = new \DSI\Entity\User();
        $this->usersRepo->insert($user);

        $project = new \DSI\Entity\Project();
        $project->setId($projectID);
        $project->setOwner($user);
        $this->projectsRepo->insert($project);
        return $project;
    }

    private function createTag(int $tagID)
    {
        $tag = new \DSI\Entity\NetworkTag();
        $tag->setId($tagID);
        $tag->setName('tag-' . $tagID);
        $this->networkTagRepo->saveAsNew($tag);
        return $tag;
    }
}