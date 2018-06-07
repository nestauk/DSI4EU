<?php

namespace Services;

use DSI\Entity\CaseStudy;
use DSI\Entity\Event;
use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\Story;
use DSI\Entity\Translation;
use DSI\Entity\User;
use DSI\Service\Translate;
use Models\ClusterLang;

class URL
{
    private $currentLanguage;

    public function __construct($lang = null)
    {
        $this->setCurrentLanguage(
            $lang ? $lang : Translate::getCurrentLang()
        );
    }

    public function dashboard($format = null)
    {
        $extension = '';
        if ($format == 'json')
            $extension = '.json';
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'dashboard' . $extension;
    }

    public function acceptPolicy()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'accept-policy';
    }

    public function notifications()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'notifications.json';
    }

    public function register()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'register';
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

    public function myProfile()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'my-profile';
    }

    public function profile(User $user)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'profile/' . $user->getId();
    }

    public function confirmPermanentLogin(User $user)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'profile/' . $user->getId() . '?src=login';
    }

    public function home()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage();
    }

    public function projects()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'projects';
    }

    public function projectTagsJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'projectTags.json';
    }

    public function organisationTagsJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'organisationTags.json';
    }

    public function projectsJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'projects.json';
    }

    public function funding()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'funding';
    }

    public function addFunding()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'funding/add';
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

    public function events()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'events';
    }

    /**
     * @param Event $event
     * @return string
     */
    public function event(Event $event)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'event/' . $event->getId();
    }

    public function addEvent()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'events/add';
    }

    public function eventEdit(Event $event)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'event/edit/' . $event->getId();
    }

    public function editEventJson($eventID)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'event/edit/' . $eventID . '.json';
    }

    public function eventsJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'events.json';
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
    public function projectJson(Project $project)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/' . $project->getId() . '.json';
    }

    /**
     * @param Project $project
     * @return string
     */
    public function projectEdit(Project $project)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/edit/' . $project->getId();
    }

    /**
     * @param Project $project
     * @return string
     */
    public function projectMembers(Project $project)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/members/' . $project->getId();
    }

    /**
     * @param Project $project
     * @return string
     */
    public function projectOwnerEdit(Project $project)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'project/editOwner/' . $project->getId();
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
    public function organisationEdit(Organisation $org)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'org/edit/' . $org->getId();
    }

    /**
     * @param Organisation $org
     * @return string
     */
    public function organisationOwnerEdit(Organisation $org)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'org/editOwner/' . $org->getId();
    }

    public function feedback()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'feedback';
    }

    public function aboutTheProject()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'about-the-project';
    }

    public function partners()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'partners';
    }

    public function openDataResearchAndResources()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'open-data-research-and-resources';
    }

    public function contactDSI()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'contact-dsi';
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

    public function blogPostAdd()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'story/add';
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

    public function blogPostEdit(Story $story)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'story/edit/' . $story->getId();
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

    public function caseStudies($format = null)
    {
        $extension = '';
        if ($format == 'json')
            $extension = '.json';
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'case-studies' . $extension;
    }

    public function search()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'search/';
    }

    public function exploreDSI()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'explore-dsi';
    }

    public function editUserProfile(User $user)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'profile/edit/' . $user->getId();
    }

    public function editUserPrivileges(User $user)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'profile/editPrivileges/' . $user->getId();
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

    public function messageCommunityAdmins()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'message/community-admins';
    }

    public function manageTags()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'manage/tags';
    }

    public function manageTagsJson()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'manage/tags.json';
    }

    public function cookiesPolicy()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'cookies-policy';
    }

    public function uploadImage()
    {
        return SITE_RELATIVE_PATH . '/uploadImage.json';
    }

    public function rssNewsBlogs()
    {
        return SITE_RELATIVE_PATH . '/rss/news-and-blogs.xml';
    }

    public function rssEvents()
    {
        return SITE_RELATIVE_PATH . '/rss/events.xml';
    }

    public function rssFundingOpportunities()
    {
        return SITE_RELATIVE_PATH . '/rss/funding-opportunities.xml';
    }

    public function terminateAccount($token)
    {
        return SITE_RELATIVE_PATH . '/terminateAccount?token=' . $token;
    }

    public function waitingApproval($format = null)
    {
        if ($format == 'json')
            $extension = '.json';
        else
            $extension = '';

        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'waiting-approval' . $extension;
    }

    public function clusters()
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'clusters';
    }

    public function cluster(ClusterLang $cluster)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'cluster/' . $cluster->getClusterId() . '/' . self::linkify($cluster->getTitle());
    }

    public function clusterEdit($clusterId)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'cluster/' . (int)$clusterId . '/edit';
    }

    public function clusterApi($clusterId)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'api/cluster/' . (int)$clusterId;
    }

    public function clusterImgApi($clusterId = null)
    {
        return SITE_RELATIVE_PATH . '/' . $this->addLanguage() . 'api/cluster-image/' . $clusterId;
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
    private function setCurrentLanguage($currentLanguage)
    {
        $this->currentLanguage = (string)$currentLanguage;
    }


    public function fullUrl($shortAbsoluteUrl)
    {
        return
            (MUST_USE_HTTPS ? 'https://' : 'http://') .
            SITE_DOMAIN .
            $shortAbsoluteUrl;
    }
}