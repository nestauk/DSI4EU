<?php
namespace DSI\Controller;

use DSI\Service\ErrorHandler;
use DSI\UseCase\Login;

class AppLoginController
{
    public function exec()
    {
        if (!isset($_GET['email']) OR !isset($_GET['password'])) {
            echo json_encode([
                'code' => 'error 1',
            ]);
            return;
        }

        $domainList = [
            'nesta.org.uk',
            'waag.org',
        ];

        $domain = explode('@', $_GET['email'], 2)[1];
        if (!in_array($domain, $domainList)) {
            echo json_encode([
                'code' => 'error',
            ]);
            return;
        }

        try {
            $login = new Login();
            $login->data()->email = $_GET['email'] ?? '';
            $login->data()->password = $_GET['password'] ?? '';
            $login->exec();

            echo json_encode([
                'code' => 'ok'
            ]);
            die();
        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getErrors(),
            ]);
        }
    }
}