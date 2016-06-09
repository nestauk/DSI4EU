<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 15:45
 */

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\UpdateUserProfilePicture;
use DSI\UseCase\UploadTempImage;

class TempGalleryController
{
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        if ($this->format == 'json') {
            if (isset($_FILES['file'])) {
                try {
                    $uploadTempImage = new UploadTempImage();
                    $uploadTempImage->data()->filePath = $_FILES['file']['tmp_name'];
                    $uploadTempImage->data()->fileName = $_FILES['file']['name'];
                    $uploadTempImage->exec();

                    echo json_encode([
                        'code' => 'ok',
                        'imgPath' => $uploadTempImage->getImagePath(),
                    ]);
                } catch (ErrorHandler $e) {
                    echo json_encode([
                        'code' => 'error',
                        'errors' => $e->getErrors(),
                    ]);
                }
            }

            die();
        }
    }
}