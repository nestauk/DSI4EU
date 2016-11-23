<?php

namespace DSI\UseCase\Organisations;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationFollow;
use DSI\Entity\User;
use DSI\Repository\OrganisationFollowRepository;
use DSI\Service\ErrorHandler;

class FollowOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var Organisation */
    private $organisation;

    /** @var User */
    private $user,
        $executor;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->followOrganisation();
    }

    private function assertExecutorCanMakeChanges()
    {
        if ($this->executor->getId() != $this->user->getId()) {
            $this->errorHandler->addTaggedError('user', 'You cannot make this change');
            throw $this->errorHandler;
        }
    }

    private function assertDataHasBeenSent()
    {
        if (!$this->executor)
            throw new \InvalidArgumentException('No executor');
        if (!$this->user)
            throw new \InvalidArgumentException('No user');
        if (!$this->organisation)
            throw new \InvalidArgumentException('No organisation');
    }

    private function followOrganisation()
    {
        $follow = new OrganisationFollow();
        $follow->setUser($this->user);
        $follow->setOrganisation($this->organisation);
        (new OrganisationFollowRepository())->add($follow);
    }

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param User $executor
     */
    public function setExecutor(User $executor)
    {
        $this->executor = $executor;
    }
}