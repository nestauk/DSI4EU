<?php

namespace DSI\Controller;

use DSI\Entity\ContentUpdate;
use DSI\Entity\User;
use DSI\Repository\ContentUpdateRepo;
use DSI\Service\Auth;
use Services\URL;
use DSI\UseCase\SecureCode;
use DSI\UseCase\WaitingApproval\ApproveWaitingApproval;
use DSI\UseCase\WaitingApproval\RejectWaitingApproval;

class WaitingApprovalController
{
    /** @var URL */
    private $urlHandler;

    /** @var User */
    private $authUser;

    public function __construct()
    {
        $this->urlHandler = new URL();
        $auth = new Auth();
        $auth->ifNotLoggedInRedirectTo($this->urlHandler->login());

        $this->authUser = $auth->getUser();
        if (!$this->authUser->isCommunityAdmin())
            go_to($this->urlHandler->dashboard());
    }

    public function html()
    {
        $urlHandler = $this->urlHandler;
        $loggedInUser = $this->authUser;

        if (isset($_POST['getSecureCode']))
            return $this->setSecureCode();

        if (isset($_POST['approveItem']))
            return $this->handleSingleApprove((int)$_POST['id']);

        if (isset($_POST['rejectItem']))
            return $this->handleSingleReject((int)$_POST['id']);

        if (isset($_POST['submit'])) {
            if ($_POST['submit'] === 'approve')
                $this->handleMultipleApproves($_POST['id']);
            if ($_POST['submit'] === 'reject')
                $this->handleMultipleRejects($_POST['id']);

            go_to('?');
        }

        $pageTitle = 'Waiting Approval';
        require __DIR__ . '/../../../www/views/waiting-approval.php';
        return true;
    }

    public function json()
    {
        $urlHandler = $this->urlHandler;
        $loggedInUser = $this->authUser;

        $contentUpdates = (new ContentUpdateRepo())
            ->getAll();

        $contentUpdates = array_map(function (ContentUpdate $contentUpdate) use ($urlHandler) {
            return [
                'id' => $contentUpdate->getId(),
                'projectID' => $contentUpdate->getProjectID(),
                'organisationID' => $contentUpdate->getOrganisationID(),
                'name' => $contentUpdate->getProjectID() ?
                    $contentUpdate->getProject()->getName() :
                    $contentUpdate->getOrganisation()->getName(),
                'url' => $contentUpdate->getProjectID() ?
                    $urlHandler->project($contentUpdate->getProject()) :
                    $urlHandler->organisation($contentUpdate->getOrganisation()),
                'updated' => $contentUpdate->getUpdated(),
            ];
        }, $contentUpdates);

        echo json_encode([
            'code' => 'ok',
            'items' => $contentUpdates
        ]);
        return true;
    }

    private function handleSingleApprove($id)
    {
        $genSecureCode = new SecureCode();
        if (!$genSecureCode->checkCode($_POST['secureCode']))
            return false;

        $this->approveItem($id);

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    private function handleMultipleApproves($ids)
    {
        foreach ($ids AS $id)
            $this->approveItem((int)$id);

        return true;
    }

    private function handleMultipleRejects($ids)
    {
        foreach ($ids AS $id)
            $this->rejectItem((int)$id);

        return true;
    }

    private function handleSingleReject($id)
    {
        $genSecureCode = new SecureCode();
        if (!$genSecureCode->checkCode($_POST['secureCode']))
            return false;

        $this->rejectItem($id);

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    private function setSecureCode()
    {
        $genSecureCode = new SecureCode();
        $genSecureCode->exec();
        echo json_encode([
            'code' => 'ok',
            'secureCode' => $genSecureCode->getCode(),
        ]);
        return true;
    }

    /**
     * @param $id
     * @throws \DSI\NotFound
     */
    private function approveItem($id)
    {
        (new ApproveWaitingApproval())
            ->setExecutor($this->authUser)
            ->setContentUpdate((new ContentUpdateRepo())->getById($id))
            ->exec();
    }

    /**
     * @param $id
     * @throws \DSI\NotFound
     */
    private function rejectItem($id)
    {
        (new RejectWaitingApproval())
            ->setExecutor($this->authUser)
            ->setContentUpdate((new ContentUpdateRepo())->getById($id))
            ->exec();
    }
}