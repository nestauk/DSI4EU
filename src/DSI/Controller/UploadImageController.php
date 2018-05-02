<?php
namespace DSI\Controller;

use DSI\Service\Auth;
use Services\URL;

class UploadImageController
{
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        if ($this->format == 'json') {
            if (isset($_POST['upload'])) {
                $accepted_origins = array("https://" . SITE_DOMAIN);
                $wwwFolder = "/images/upload/" . date('Y/m/');
                $serverFolder = __DIR__ . "/../../../www" . $wwwFolder;
                $tempImage = $_FILES['file'];

                if (is_uploaded_file($tempImage['tmp_name'])) {
                    if (isset($_SERVER['HTTP_ORIGIN'])) {
                        // same-origin requests won't set an origin. If the origin is set, it must be valid.
                        if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                        } else {
                            header("HTTP/1.0 403 Origin Denied");
                            return;
                        }
                    }

                    /*
                      If your script needs to receive cookies, set images_upload_credentials : true in
                      the configuration and enable the following two headers.
                    */
                    // header('Access-Control-Allow-Credentials: true');
                    // header('P3P: CP="There is no P3P policy."');

                    // Sanitize input
                    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $tempImage['name'])) {
                        header("HTTP/1.0 500 Invalid file name.");
                        return;
                    }

                    // Verify extension
                    if (!in_array(strtolower(pathinfo($tempImage['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                        header("HTTP/1.0 500 Invalid extension.");
                        return;
                    }

                    if (!file_exists($serverFolder)) {
                        mkdir($serverFolder, 0750, true);
                    }

                    // Accept upload if there was no origin, or if it is an accepted origin
                    $serverFilename = $serverFolder . $tempImage['name'];
                    move_uploaded_file($tempImage['tmp_name'], $serverFilename);

                    // Respond to the successful upload with JSON.
                    // Use a location key to specify the path to the saved image resource.
                    $imageSize = getimagesize($serverFilename);
                    echo json_encode([
                        'location' => SITE_RELATIVE_PATH . $wwwFolder . $tempImage['name'],
                        'name' => $tempImage['name'],
                        'width' => $imageSize[0],
                        'height' => $imageSize[1],
                    ]);
                } else {
                    // Notify editor that the upload failed
                    header("HTTP/1.0 500 Server Error");
                }
            }
        }
    }
}