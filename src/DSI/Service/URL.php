<?php
namespace DSI\Service;


use DSI\Entity\CaseStudy;
use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\Story;
use DSI\Entity\Translation;

class URL
{
    private $currentLanguage;

    public function __construct()
    {
        $this->currentLanguage = Translate::getCurrentLang();
    }

    public function dashboard()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'dashboard';
    }

    public function myProfile()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'my-profile';
    }

    public function login()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'login';
    }

    public function loginJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'login.json';
    }

    public function logout()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'logout';
    }

    public function profile($userID)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'profile/' . $userID;
    }

    public function home()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage();
    }

    public function projects()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'projects';
    }

    public function projectsJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'projects.json';
    }

    public function project(Project $project)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/' . $project->getId() . '/' . self::linkify($project->getName());
    }

    public function editProject($projectID)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/edit/' . $projectID;
    }

    public function organisations($format = null)
    {
        $extension = '';
        if ($format == 'json')
            $extension = '.json';
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'organisations' . $extension;
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

    /**
     * @return string
     */
    private function addLanguage()
    {
        if ($this->currentLanguage != Translation::DEFAULT_LANGUAGE)
            return $this->currentLanguage . '/';

        return '';
    }

    /**
     * @param string $currentLanguage
     */
    public function setCurrentLanguage($currentLanguage)
    {
        $this->currentLanguage = (string)$currentLanguage;
    }
}