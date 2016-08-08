<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 16:11
 */

namespace DSI\Service;


use DSI\Entity\CaseStudy;
use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\Story;

class URL
{
    public static function dashboard()
    {
        return SITE_RELATIVE_PATH . '/dashboard';
    }

    public static function myProfile()
    {
        return SITE_RELATIVE_PATH . '/my-profile';
    }

    public static function login()
    {
        return SITE_RELATIVE_PATH . '/login';
    }

    public static function logout()
    {
        return SITE_RELATIVE_PATH . '/logout';
    }

    public static function profile($userID)
    {
        return SITE_RELATIVE_PATH . '/profile/' . $userID;
    }

    public static function home()
    {
        return SITE_RELATIVE_PATH . '/';
    }

    public static function projects()
    {
        return SITE_RELATIVE_PATH . '/projects';
    }

    public static function project(Project $project)
    {
        return SITE_RELATIVE_PATH . '/project/' . $project->getId() . '/' . self::linkify($project->getName());
    }

    /**
     * @param int $projectID
     * @return string
     */
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
        return SITE_RELATIVE_PATH . '/org/edit/' . $org->getId();
    }

    public static function feedback()
    {
        return SITE_RELATIVE_PATH . '/feedback';
    }

    public static function updates()
    {
        return SITE_RELATIVE_PATH . '/updates';
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

    public static function blogPosts()
    {
        return SITE_RELATIVE_PATH . '/blog';
    }

    public static function blogPost(Story $story)
    {
        return SITE_RELATIVE_PATH . '/blog/' . $story->getId() . '/' . self::linkify($story->getTitle());
    }

    public static function caseStudy(CaseStudy $caseStudy)
    {
        return SITE_RELATIVE_PATH . '/case-study/' . $caseStudy->getId() . '/' . self::linkify($caseStudy->getTitle());
    }

    public static function addCaseStudy()
    {
        return SITE_RELATIVE_PATH . '/case-study/add';
    }

    public static function caseStudyEdit(CaseStudy $caseStudy)
    {
        return SITE_RELATIVE_PATH . '/case-study/edit/' . $caseStudy->getId();
    }

    public static function caseStudies()
    {
        return SITE_RELATIVE_PATH . '/case-studies';
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

    public static function termsOfUse()
    {
        return SITE_RELATIVE_PATH . '/terms-of-use';
    }

    public static function privacyPolicy()
    {
        return SITE_RELATIVE_PATH . '/privacy-policy';
    }

    public static function sitemapXML()
    {
        return SITE_RELATIVE_PATH . '/sitemap.xml';
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