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
use Services\App;
use Services\View;

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
        $owner = $this->contentUpdate->getOwner();
        if ($this->contentUpdate->hasProject()) {
            $subject = 'Your project has been approved!';
            $message = View::prepare(__DIR__ . '/../../../../resources/views/emails/approvedProject.php', [
                'subject' => $subject,
                'project' => $this->contentUpdate->getProject(),
                'owner' => $owner,
            ]);
        } else {
            $subject = 'Your organisation has been approved!';
            $message = View::prepare(__DIR__ . '/../../../../resources/views/emails/approvedOrganisation.php', [
                'subject' => $subject,
                'organisation' => $this->contentUpdate->getOrganisation(),
                'owner' => $owner,
            ]);
        }

        if ($owner) {
            $email = new Mailer();
            $email->From = 'noreply@digitalsocial.eu';
            $email->FromName = 'Digital Social Innovation';
            $email->addAddress($owner->getEmail());
            $email->Subject = $subject;
            $email->wrapMessageInTemplate([
                'header' => $subject,
                'body' => $message,
            ]);
            $email->isHTML(true);
            $email->send();
        }
    }
}