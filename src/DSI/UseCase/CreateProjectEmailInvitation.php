<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectEmailInvitation;
use DSI\Entity\User;
use DSI\Repository\ProjectEmailInvitationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;
use DSI\Service\URL;

class CreateProjectEmailInvitation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectEmailInvitationRepository */
    private $projectEmailInvitationRepo;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var User */
    private $byUser;

    /** @var string */
    private $email;

    /** @var boolean */
    private $sendEmail;

    /** @var int */
    private $projectID;

    public function exec()
    {
        $urlHandler = new URL();

        $this->errorHandler = new ErrorHandler();
        $this->projectEmailInvitationRepo = new ProjectEmailInvitationRepository();
        $this->projectRepository = new ProjectRepository();
        $this->userRepository = new UserRepository();

        if ($this->projectEmailInvitationRepo->projectInvitedEmail($this->projectID, $this->email)) {
            $this->errorHandler->addTaggedError('email', __('This user has already been invited to join the project'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectEmailInvitation = new ProjectEmailInvitation();
        $projectEmailInvitation->setByUser($this->byUser);
        $projectEmailInvitation->setProject($this->projectRepository->getById($this->projectID));
        $projectEmailInvitation->setEmail($this->email);
        $this->projectEmailInvitationRepo->add($projectEmailInvitation);

        $this->sendEmail($this->byUser, $urlHandler);
    }

    /**
     * @param User $byUser
     * @param URL $urlHandler
     */
    private function sendEmail(User $byUser, URL $urlHandler)
    {
        if ($this->sendEmail) {
            $message = "{$byUser->getFirstName()} {$byUser->getLastName()} has invited you to join DSI.<br />";
            $message .= "<a href='http://" . SITE_DOMAIN . $urlHandler->home() . "'>Click here</a> to register";
            $email = new Mailer();
            $email->From = 'noreply@digitalsocial.eu';
            $email->FromName = 'Digital Social';
            $email->addAddress($this->email);
            $email->Subject = 'Digital Social Innovation :: Invitation';
            $email->wrapMessageInTemplate([
                'header' => 'Invitation',
                'body' => $message,
            ]);
            $email->isHTML(true);
            $email->send();
        }
    }

    /**
     * @param User $byUser
     */
    public function setByUser(User $byUser)
    {
        $this->byUser = $byUser;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param bool $sendEmail
     */
    public function setSendEmail(bool $sendEmail)
    {
        $this->sendEmail = $sendEmail;
    }

    /**
     * @param int $projectID
     */
    public function setProjectID(int $projectID)
    {
        $this->projectID = $projectID;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->projectID = $project->getId();
    }
}