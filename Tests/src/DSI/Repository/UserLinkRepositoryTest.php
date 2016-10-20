<?php

require_once __DIR__ . '/../../../config.php';

class UserLinkRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\UserLinkRepository */
    protected $userLinkRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $userRepo;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    /** @var string */
    protected $link_1, $link_2, $link_3;

    public function setUp()
    {
        $this->userLinkRepo = new \DSI\Repository\UserLinkRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user_1 = $this->createUser(1);
        $this->user_2 = $this->createUser(2);
        $this->user_3 = $this->createUser(3);
        $this->link_1 = 'http://example.com/';
        $this->link_2 = 'http://google.com/';
        $this->link_3 = 'http://yahoo.com/';
    }

    public function tearDown()
    {
        $this->userLinkRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function userLinkCanBeSaved()
    {
        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_1);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_2);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_2);
        $userLink->setLink($this->link_1);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_3);
        $userLink->setLink($this->link_1);
        $this->userLinkRepo->add($userLink);

        $this->assertCount(2, $this->userLinkRepo->getByUser(
            $this->user_1
        ));
    }

    /** @test */
    public function cannotAddSameUserLinkTwice()
    {
        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_1);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->userLinkRepo->add($userLink);
    }

    /** @test saveAsNew */
    public function canCheckIfUserHasLink()
    {
        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_1);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_2);
        $userLink->setLink($this->link_2);
        $this->userLinkRepo->add($userLink);

        $this->assertTrue($this->userLinkRepo->userHasLink(
            $this->user_1->getId(), $this->link_1)
        );
        $this->assertFalse($this->userLinkRepo->userHasLink(
            $this->user_1->getId(), $this->link_2)
        );
        $this->assertTrue($this->userLinkRepo->userHasLink(
            $this->user_2->getId(), $this->link_2)
        );
        $this->assertFalse($this->userLinkRepo->userHasLink(
            $this->user_2->getId(), $this->link_1)
        );
    }

    /** @test saveAsNew */
    public function canGetLinksByUserID()
    {
        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_1);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_2);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_2);
        $userLink->setLink($this->link_3);
        $this->userLinkRepo->add($userLink);

        $this->assertEquals(
            [$this->link_1, $this->link_2],
            $this->userLinkRepo->getLinksByUserID($this->user_1->getId())
        );
        $this->assertEquals(
            [$this->link_3],
            $this->userLinkRepo->getLinksByUserID($this->user_2->getId())
        );
    }

    /** @test remove */
    public function canRemoveLinkFromUser()
    {
        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_1);
        $this->userLinkRepo->add($userLink);

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_2);
        $this->userLinkRepo->add($userLink);

        $this->assertEquals(
            [$this->link_1, $this->link_2],
            $this->userLinkRepo->getLinksByUserID($this->user_1->getId())
        );

        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_2);
        $this->userLinkRepo->remove($userLink);

        $this->assertEquals(
            [$this->link_1],
            $this->userLinkRepo->getLinksByUserID($this->user_1->getId())
        );
    }

    /** @test remove */
    public function cannotRemoveNonexistentLinkFromUser()
    {
        $userLink = new \DSI\Entity\UserLink();
        $userLink->setUser($this->user_1);
        $userLink->setLink($this->link_2);
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userLinkRepo->remove($userLink);
    }

    private function createUser(int $userID)
    {
        $user = new \DSI\Entity\User();
        $user->setId($userID);
        $this->userRepo->insert($user);
        return $user;
    }
}