<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 15:45
 */

namespace DSI\Controller;

use DSI\Entity\Image;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\UploadTempImage;

class TempGalleryController
{
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if ($this->format == 'json') {
            try {
                if (isset($_FILES['file'])) {

                    $uploadTempImage = new UploadTempImage();
                    $uploadTempImage->data()->format = $_POST['format'] ?? '';
                    $uploadTempImage->data()->filePath = $_FILES['file']['tmp_name'];
                    $uploadTempImage->data()->fileName = $_FILES['file']['name'];
                    $uploadTempImage->exec();

                    echo json_encode([
                        'code' => 'ok',
                        'imgPath' => Image::TEMP_FOLDER_URL . $uploadTempImage->getImagePath(),
                    ]);
                }
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }

            return;
        }
    }
}