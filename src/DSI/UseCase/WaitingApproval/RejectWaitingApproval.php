<?php

namespace DSI\UseCase\WaitingApproval;

use DSI\AccessDenied;
use DSI\Entity\ContentUpdate;
use DSI\Entity\User;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;
use DSI\UseCase\ContentUpdates\RemoveContentUpdate;
use DSI\UseCase\Organisations\RemoveOrganisation;
use DSI\UseCase\Projects\RemoveProject;

class RejectWaitingApproval
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
            $exec = new RemoveProject();
            $exec->data()->executor = $this->executor;
            $exec->data()->project = $this->contentUpdate->getProject();
            $exec->exec();
        } else {
            $exec = new RemoveOrganisation();
            $exec->data()->executor = $this->executor;
            $exec->data()->organisation = $this->contentUpdate->getOrganisation();
            $exec->exec();
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
     * @return RejectWaitingApproval
     */
    public function setExecutor(User $executor)
    {
        $this->executor = $executor;
        return $this;
    }

    /**
     * @param ContentUpdate $contentUpdate
     * @return RejectWaitingApproval
     */
    public function setContentUpdate(ContentUpdate $contentUpdate)
    {
        $this->contentUpdate = $contentUpdate;
        return $this;
    }
}