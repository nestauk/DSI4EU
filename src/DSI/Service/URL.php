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

    public function dashboard($format = null)
    {
        $extension = '';
        if ($format == 'json')
            $extension = '.json';
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'dashboard' . $extension;
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

    public function funding()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'funding';
    }

    public function editFunding($fundingID)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'funding/edit/' . $fundingID;
    }

    public function editFundingJson($fundingID)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'funding/edit/' . $fundingID . '.json';
    }

    public function fundingJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'funding.json';
    }

    /**
     * @param Project $project
     * @return string
     */
    public function project(Project $project)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/' . $project->getId() . '/' . self::linkify($project->getName());
    }

    /**
     * @param Project $project
     * @return string
     */
    public function editProject(Project $project)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/edit/' . $project->getId();
    }

    public function organisations($format = null)
    {
        $extension = '';
        if ($format == 'json')
            $extension = '.json';
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'organisations' . $extension;
    }

    public function organisation(Organisation $org)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'org/' . $org->getId() . '/' . self::linkify($org->getName());
    }

    /**
     * @param Organisation $org
     * @return string
     */
    public function editOrganisation(Organisation $org)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'org/edit/' . $org->getId();
    }

    public function feedback()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'feedback';
    }

    public function updates()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'updates';
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

    public function blogPosts($format = null)
    {
        $extension = '';
        if ($format == 'json')
            $extension = '.json';
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'blog' . $extension;
    }

    public function blogPost(Story $story)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'blog/' . $story->getId() . '/' . self::linkify($story->getTitle());
    }

    public function blogPostEdit($id)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'story/edit/' . $id;
    }

    public function caseStudy(CaseStudy $caseStudy)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'case-study/' . $caseStudy->getId() . '/' . self::linkify($caseStudy->getTitle());
    }

    public function addCaseStudy()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'case-study/add';
    }

    public function caseStudyEdit(CaseStudy $caseStudy)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'case-study/edit/' . $caseStudy->getId();
    }

    public function caseStudies()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'case-studies';
    }

    public function search()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'search/';
    }

    public function exploreDSI()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'explore-dsi';
    }

    public function editProfile()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'personal-details';
    }

    public function termsOfUse()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'terms-of-use';
    }

    public function privacyPolicy()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'privacy-policy';
    }

    public function sitemapXML()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'sitemap.xml';
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