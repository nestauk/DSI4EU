<?php

namespace DSI\UseCase;

use DSI\Repository\CacheMailRepository;

class SendCachedEmails
{
    public function exec()
    {
        $cacheMailRepository = new CacheMailRepository();
        $cachedEmails = $cacheMailRepository->getAll();
        foreach ($cachedEmails AS $email) {
            $email->getContent()->send();
            $cacheMailRepository->remove($email);
        }
    }
}