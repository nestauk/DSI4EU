<?php

namespace DSI\Controller;

use DSI\NotFound;
use DSI\Repository\TerminateAccountTokenRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use DSI\Service\URL;
use DSI\UseCase\TerminateAccount\TerminateAccount;

class TerminateAccountController
{
    public function exec()
    {
        $urlHandler = new URL();
        $auth = new Auth();
        $auth->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $auth->getUser();

        try {
            $token = (new TerminateAccountTokenRepo())->getByToken($_GET['token']);
        } catch (NotFound $e) {
            return $this->showErrorPage();
        }

        if ($token->isExpired())
            return $this->showErrorPage();

        try {
            if (isset($_POST['confirm'])) {
                $exec = new TerminateAccount();
                $exec->setUser($loggedInUser);
                $exec->setToken($token);
                $exec->exec();

                echo json_encode([
                    'code' => 'ok'
                ]);

                return null;
            }
        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getErrors(),
            ]);
            return null;
        }

        JsModules::setTranslations(true);
        require __DIR__ . '/../../../www/views/terminate-account.php';

        return null;
    }

    /**
     * @return null
     */
    private function showErrorPage()
    {
        require __DIR__ . '/../../../www/views/terminate-account-error.php';
        return null;
    }
}