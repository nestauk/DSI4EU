<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectEmailInvitation;
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

    /** @var CreateProjectEmailInvitation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateProjectEmailInvitation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectEmailInvitationRepo = new ProjectEmailInvitationRepository();
        $this->projectRepository = new ProjectRepository();
        $this->userRepository = new UserRepository();

        if ($this->projectEmailInvitationRepo->projectInvitedEmail($this->data()->projectID, $this->data()->email)) {
            $this->errorHandler->addTaggedError('email', 'This user has already been invited to the project');
            $this->errorHandler->throwIfNotEmpty();
        }

        $byUser = $this->userRepository->getById($this->data()->byUserID);

        $projectEmailInvitation = new ProjectEmailInvitation();
        $projectEmailInvitation->setByUser($byUser);
        $projectEmailInvitation->setProject($this->projectRepository->getById($this->data()->projectID));
        $projectEmailInvitation->setEmail($this->data()->email);
        $this->projectEmailInvitationRepo->add($projectEmailInvitation);

        $message = "{$byUser->getFirstName()} {$byUser->getLastName()} has invited you to join DSI.<br />";
        $message .= "<a href='http://" . SITE_DOMAIN . URL::home() . "'>Click here</a> to register";
        $email = new Mailer();
        $email->From = 'noreply@digitalsocial.eu';
        $email->FromName = 'No Reply';
        $email->addAddress($this->data()->email);
        $email->Subject = 'Digital Social Innovation :: Invitation';
        $email->wrapMessageInTemplate([
            'header' => 'Invitation',
            'body' => $message,
        ]);
        $email->isHTML(true);
        $email->send();
    }

    /**
     * @return CreateProjectEmailInvitation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class CreateProjectEmailInvitation_Data
{
    /** @var int */
    public $byUserID,
        $projectID;

    /** @var string */
    public $email;

    /** @var boolean */
    public $sendEmail;
}