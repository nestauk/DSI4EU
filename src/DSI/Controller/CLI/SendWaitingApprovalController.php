<?php

namespace DSI\Controller\CLI;

use DSI\Repository\ContentUpdateRepo;
use Services\App;
use DSI\Service\Mailer;

class SendWaitingApprovalController
{
    /** @var  String[] */
    private $args;

    public function exec()
    {
        $yesterday = (new \DateTimeImmutable(date('Y-m-d')))
            ->sub(new \DateInterval('P1D'));

        $contentUpdates = (new ContentUpdateRepo())
            ->getByDate($yesterday->format('Y-m-d'));

        ob_start();
        require(__DIR__ . '/../../../../resources/views/emails/send-waiting-approval.php');
        $message = "<div>";
        $message .= ob_get_clean();
        $message .= "</div>";

        $email = new Mailer();
        $email->From = 'noreply@digitalsocial.eu';
        $email->FromName = 'Digital Social';
        $email->addAddress(App::getWaitingApprovalEmailAddress());
        $email->Subject = 'Digital Social Innovation :: Waiting Approval Projects & Organisations';
        $email->wrapMessageInTemplate([
            'header' => 'Waiting Approval Projects & Organisations',
            'body' => $message
        ]);
        $email->isHTML(true);
        $email->send();
    }

    /**
     * @param \String[] $args
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }
}