<?php

namespace DSI\UseCase\WaitingApproval;

use DSI\AccessDenied;
use DSI\Entity\ContentUpdate;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;
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

        (new RemoveContentUpdate($this->contentUpdate))->exec();

        $this->informUser();
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

    private function informUser()
    {
        ob_start();
        if ($this->contentUpdate->hasProject()) {
            $owner = $this->contentUpdate->getProject()->getOwner();
            $subject = 'Your project has been approved';
            require(__DIR__ . '/../../../../resources/views/emails/approvedProject.php');
        } else {
            $owner = $this->contentUpdate->getOrganisation()->getOwner();
            $subject = 'Your organisation has been approved';
            require(__DIR__ . '/../../../../resources/views/emails/approvedOrganisation.php');
        }

        if ($owner) {
            $message = "<div>";
            $message .= ob_get_clean();
            $message .= "</div>";

            $email = new Mailer();
            $email->From = 'noreply@digitalsocial.eu';
            $email->FromName = 'Digital Social';
            $email->addAddress($owner->getEmail());
            $email->Subject = $subject;
            $email->wrapMessageInTemplate([
                'header' => $subject,
                'body' => $message
            ]);
            $email->isHTML(true);
            $email->send();
        }
    }
}