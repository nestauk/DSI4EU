<?php

namespace Services;

class Request
{
    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PURGE = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    /** @var null \Symfony\Component\HttpFoundation\Request */
    private static $instance = null;

    private static function getInstance()
    {
        if (!self::$instance)
            self::$instance = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        return self::$instance;
    }

    public static function isMethod($method)
    {
        return self::getInstance()->isMethod($method);
    }

    public static function isPost()
    {
        return self::isMethod(self::METHOD_POST);
    }

    public static function isGet()
    {
        return self::isMethod(self::METHOD_GET);
    }

    public static function isDelete()
    {
        return self::isMethod(self::METHOD_DELETE);
    }
}