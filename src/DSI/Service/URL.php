<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 16:11
 */

namespace DSI\Service;


class URL
{
    public static function myProfile()
    {
        return SITE_RELATIVE_PATH . '/my_profile';
    }

    public static function login()
    {
        return SITE_RELATIVE_PATH . '/login';
    }

    public static function profile($userProfileURL)
    {
        return SITE_RELATIVE_PATH . '/profile/' . $userProfileURL;
    }

    public static function home()
    {
        return SITE_RELATIVE_PATH . '/';
    }
}