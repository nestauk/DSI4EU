<?php

namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\Mailer;
use DSI\Service\URL;
use DSI\UseCase\SendEmailToCommunityAdmins;

class MessageCommunityAdminsController
{
    public function __construct()
    {
    }

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!$loggedInUser->isCommunityAdmin())
            go_to($urlHandler->home());

        $sessionKey = 'secureCode-messageCommunityAdmins';

        if (isset($_POST['save'])) {
            if ($_POST['secureCode'] == $_SESSION[$sessionKey]) {
                try {
                    if (trim($_POST['message']) != '') {
                        $message = '<a href="https://' . SITE_DOMAIN . $urlHandler->profile($loggedInUser) . '">DSI user: ' .
                            $loggedInUser->getFullName() .
                            '</a> has sent the following message to all DSI community admins.';
                        $message .= '<br /><br />';
                        $message .= '<i>"' . nl2br(show_input($_POST['message'])) . '"</i>';
                        $message .= '<br /><br />';
                        $message .= 'Click <a href="http://' . SITE_DOMAIN . $urlHandler->messageCommunityAdmins() . '">here</a> ' .
                            'to reply to this email. ' .
                            'Your message will be sent to all DSI community admins.';
                        $mail = new Mailer();
                        $mail->From = 'noreply@digitalsocial.eu';
                        $mail->FromName = 'Digital Social';
                        $mail->Subject = 'Mass Message to DSI Community Admins';
                        $mail->wrapMessageInTemplate([
                            'header' => 'Mass Message to DSI Community Admins',
                            'body' => $message
                        ]);

                        $exec = new SendEmailToCommunityAdmins();
                        $exec->data()->executor = $loggedInUser;
                        $exec->data()->mail = $mail;
                        $exec->exec();

                        $_SESSION['success'] = 'Message has been sent to all DSI community admins.';
                        go_to();
                        return;
                    }
                } catch (\Exception $e) {
                    pr($e);
                }
            }
        }

        $securityCode = base64_encode(openssl_random_pseudo_bytes(128));
        $_SESSION[$sessionKey] = $securityCode;

        require __DIR__ . '/../../../www/views/message-community-admins.php';

        return true;
    }
}