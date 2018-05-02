<?php

namespace Services;

class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = array(), bool $json = false)
    {
        parent::__construct(['object' => $data], $status, $headers, $json);
    }
}