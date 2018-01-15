<?php

namespace DSI\UseCase\WaitingApproval;

use DSI\AccessDenied;
use DSI\Entity\ContentUpdate;
use DSI\Entity\User;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;
use DSI\UseCase\ContentUpdates\RemoveContentUpdate;

class ApproveWaitingApproval
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var User */
    private $executor;

    /** @var ContentUpdate */
    private $contentUpdate;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->assertExecutorCanExecute();

        if ($this->contentUpdate->hasProject()) {
            $this->contentUpdate->getProject()->setIsWaitingApproval(false);
            (new ProjectRepoInAPC())->save($this->contentUpdate->getProject());
        } else {
            $this->contentUpdate->getOrganisation()->setIsWaitingApproval(false);
            (new OrganisationRepoInAPC())->save($this->contentUpdate->getOrganisation());
        }

        (new RemoveContentUpdate())
            ->setContentUpdate($this->contentUpdate)
            ->exec();
    }

    private function assertExecutorCanExecute()
    {
        if (!$this->executor->isSysAdmin())
            throw new AccessDenied('You are not allowed to perform this action');
    }

    /**
     * @param User $executor
     * @return ApproveWaitingApproval
     */
    public function setExecutor(User $executor)
    {
        $this->executor = $executor;
        return $this;
    }

    /**
     * @param ContentUpdate $contentUpdate
     * @return ApproveWaitingApproval
     */
    public function setContentUpdate(ContentUpdate $contentUpdate)
    {
        $this->contentUpdate = $contentUpdate;
        return $this;
    }
}