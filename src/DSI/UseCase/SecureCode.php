<?php

namespace DSI\UseCase;

class SecureCode
{
    public function __construct()
    {
    }

    public function exec()
    {
        $code = base64_encode(openssl_random_pseudo_bytes(128));
        $_SESSION['secureCode'] = $code;
    }

    public function getCode()
    {
        return $_SESSION['secureCode'];
    }

    public function checkCode($code)
    {
        return $_SESSION['secureCode'] == $code;
    }
}