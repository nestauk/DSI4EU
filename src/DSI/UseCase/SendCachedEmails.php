<?php

namespace DSI\UseCase;

use DSI\Repository\CacheMailRepo;

class SendCachedEmails
{
    public function exec()
    {
        $cacheMailRepository = new CacheMailRepo();
        $cachedEmails = $cacheMailRepository->getAll();
        foreach ($cachedEmails AS $email) {
            $email->getContent()->send();
            $cacheMailRepository->remove($email);
        }
    }
}