<?php

namespace Controllers;

use DSI\Entity\Image;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Actions\Uploads\UploadFile;
use Services\JsonResponse;
use Services\Response;
use Services\URL;

class UploadController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();
        if (!$loggedInUser->isEditorialAdmin())
            go_to($urlHandler->dashboard());

        try {
            if (isset($_FILES['file'])) {

                $uploadFile = new UploadFile();
                $uploadFile->filePath = $_FILES['file']['tmp_name'];
                $uploadFile->fileName = $_FILES['file']['name'];
                $uploadFile->exec();

                return (new JsonResponse([
                    'path' => Image::UPLOAD_FOLDER_URL . $uploadFile->getImagePath(),
                ], Response::HTTP_OK))->send();
            }
        } catch (ErrorHandler $e) {
            return (new JsonResponse([
                'errors' => $e->getErrors(),
            ], Response::HTTP_FORBIDDEN))->send();
        }

        return true;
    }
}