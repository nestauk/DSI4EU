<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 16:11
 */

namespace DSI\Service;


use DSI\Entity\Organisation;

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

    public static function projects()
    {
        return SITE_RELATIVE_PATH . '/projects';
    }

    public static function project($projectID, $projectName = null)
    {
        if ($projectName)
            return SITE_RELATIVE_PATH . '/project/' . $projectID . '/' . self::linkify($projectName);
        else
            return SITE_RELATIVE_PATH . '/project/' . $projectID;
    }

    public static function editProject($projectID)
    {
        return SITE_RELATIVE_PATH . '/project/edit/' . $projectID;
    }

    public static function organisations()
    {
        return SITE_RELATIVE_PATH . '/organisations';
    }

    public static function organisation(Organisation $org)
    {
        return SITE_RELATIVE_PATH . '/org/' . $org->getId() . '/' . self::linkify($org->getName());
    }

    public static function editOrganisation(Organisation $org)
    {
        return SITE_RELATIVE_PATH . '/org/' . $org->getId();
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

    public static function stories()
    {
        return SITE_RELATIVE_PATH . '/stories';
    }

    public static function story($id, $title)
    {
        return SITE_RELATIVE_PATH . '/story/' . $id . '/' . self::linkify($title);
    }

    public static function storyEdit($id)
    {
        return SITE_RELATIVE_PATH . '/story/edit/' . $id;
    }

    public static function search()
    {
        return SITE_RELATIVE_PATH . '/search/';
    }

    public static function exploreDSI()
    {
        return SITE_RELATIVE_PATH . '/explore-dsi';
    }

    public static function editProfile()
    {
        return SITE_RELATIVE_PATH . '/personal-details';
    }

    /**
     * @param $title
     * @return string
     */
    public static function linkify($title)
    {
        $title = preg_replace('([^a-zA-Z0-9\-])', '-', $title);
        $title = trim($title, '-');
        $title = preg_replace('(\-{2,})', '-', $title);
        return strtolower($title);
    }
}