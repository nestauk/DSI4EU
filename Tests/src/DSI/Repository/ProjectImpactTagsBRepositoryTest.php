<?php

require_once __DIR__ . '/../../../config.php';

class ProjectImpactTagsBRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectImpactTagBRepository */
    protected $projectTagsRepo;

    /** @var \DSI\Repository\ProjectRepository */
    protected $projectsRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $usersRepo;

    /** @var \DSI\Entity\Project */
    protected $project_1, $project_2, $project_3;

    /** @var \DSI\Repository\ImpactTagRepository */
    protected $tagsRepo;

    /** @var \DSI\Entity\ImpactTag */
    protected $tag_1, $tag_2, $tag_3;

    public function setUp()
    {
        $this->projectTagsRepo = new \DSI\Repository\ProjectImpactTagBRepository();
        $this->projectsRepo = new \DSI\Repository\ProjectRepository();
        $this->tagsRepo = new \DSI\Repository\ImpactTagRepository();
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
        $this->projectTagsRepo->clearAll();
        $this->projectsRepo->clearAll();
        $this->tagsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectTagCanBeSaved()
    {
        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_2);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_3);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $this->assertCount(2, $this->projectTagsRepo->getByProjectID(1));
    }

    /** @test saveAsNew */
    public function cannotAddSameProjectImpactTagBTwice()
    {
        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->projectTagsRepo->add($projectTag);
    }

    /** @test saveAsNew */
    public function getAllTagIDsForProject()
    {
        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_2);
        $this->projectTagsRepo->add($projectTag);

        $this->assertEquals([1, 2], $this->projectTagsRepo->getTagIDsForProject(1));
    }

    /** @test saveAsNew */
    public function getAllProjectsForTag()
    {
        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $this->assertCount(2, $this->projectTagsRepo->getByTagID(1));
    }

    /** @test saveAsNew */
    public function getAllProjectIDsForTag()
    {
        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $this->assertEquals([1, 2], $this->projectTagsRepo->getProjectIDsForTag(1));
    }

    /** @test saveAsNew */
    public function canCheckIfProjectHasTagName()
    {
        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_2);
        $this->projectTagsRepo->add($projectTag);

        $this->assertTrue($this->projectTagsRepo->projectHasTagName(
            $this->project_1->getId(), $this->tag_1->getName())
        );
        $this->assertFalse($this->projectTagsRepo->projectHasTagName(
            $this->project_1->getId(), $this->tag_2->getName())
        );
        $this->assertTrue($this->projectTagsRepo->projectHasTagName(
            $this->project_2->getId(), $this->tag_2->getName())
        );
        $this->assertFalse($this->projectTagsRepo->projectHasTagName(
            $this->project_2->getId(), $this->tag_1->getName())
        );
    }

    /** @test saveAsNew */
    public function canGetTagNamesByProjectID()
    {
        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_1);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_1);
        $projectTag->setTag($this->tag_2);
        $this->projectTagsRepo->add($projectTag);

        $projectTag = new \DSI\Entity\ProjectImpactTagB();
        $projectTag->setProject($this->project_2);
        $projectTag->setTag($this->tag_3);
        $this->projectTagsRepo->add($projectTag);

        $this->assertEquals(
            [$this->tag_1->getName(), $this->tag_2->getName()],
            $this->projectTagsRepo->getTagsNameByProjectID($this->project_1->getId())
        );
        $this->assertEquals(
            [$this->tag_3->getName()],
            $this->projectTagsRepo->getTagsNameByProjectID($this->project_2->getId())
        );
    }


    private function createProject(int $projectID)
    {
        $user = new \DSI\Entity\User();
        $this->usersRepo->saveAsNew($user);

        $project = new \DSI\Entity\Project();
        $project->setId($projectID);
        $project->setOwner($user);
        $this->projectsRepo->saveAsNew($project);
        return $project;
    }

    private function createTag(int $tagID)
    {
        $tag = new \DSI\Entity\ImpactTag();
        $tag->setId($tagID);
        $tag->setName('tag-' . $tagID);
        $this->tagsRepo->saveAsNew($tag);
        return $tag;
    }
}