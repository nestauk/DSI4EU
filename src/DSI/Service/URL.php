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
        return SITE_RELATIVE_PATH . '/my-profile';
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

    public static function project($projectID, $projectName = null)
    {
        if ($projectName)
            return SITE_RELATIVE_PATH . '/project/' . $projectID . '/' . strtolower(preg_replace('([^a-zA-Z0-9\-])', '-', $projectName));
        else
            return SITE_RELATIVE_PATH . '/project/' . $projectID;
    }

    public static function organisation($orgID, $organisationName = null)
    {
        if ($organisationName)
            return SITE_RELATIVE_PATH . '/org/' . $orgID . '/' . strtolower(preg_replace('([^a-zA-Z0-9\-])', '-', $organisationName));
        else
            return SITE_RELATIVE_PATH . '/org/' . $orgID;
    }

    public static function feedback()
    {
        return SITE_RELATIVE_PATH . '/feedback';
    }

    public static function loginWithGitHub()
    {
        return SITE_RELATIVE_PATH . '/github-login';
    }

    public static function loginWithFacebook()
    {
        return SITE_RELATIVE_PATH . '/facebook-login';
    }

    public static function loginWithGoogle()
    {
        return SITE_RELATIVE_PATH . '/google-login';
    }

    public static function loginWithTwitter()
    {
        return SITE_RELATIVE_PATH . '/twitter-login';
    }

    public static function addStory()
    {
        return SITE_RELATIVE_PATH . '/story/add';
    }

    public static function story($id, $title)
    {
        return SITE_RELATIVE_PATH . '/story/' . $id . '/' . strtolower(preg_replace('([^a-zA-Z0-9\-])', '-', $title));
    }
}