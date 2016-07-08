<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Entity\User;

class UserRepositoryTest extends PHPUnit_Framework_TestCase
{
    const EMAIL = 'test@example.com';
    const FIRST_NAME = 'firstName';
    const LAST_NAME = 'lastName';
    const BIO = 'My Own Personal Bio';
    const CITY_NAME = 'London';
    const COUNTRY_NAME = 'UK';
    const JOB_TITLE = 'WebDev at Inoveb';
    const PASSWORD = 'password';
    const FACEBOOK_UID = 'facebookUID';
    const GOOGLE_UID = 'googleUID';
    const GIT_HUB_UID = 'gitHubUID';
    const TWITTER_UID = 'twitterUID';
    const PROFILE_URL = 'profileURL';
    const PROFILE_PIC = 'profilePic.jpg';
    const COMPANY = 'company';
    const SHOW_EMAIL = self::IS_ADMIN;
    const IS_ADMIN = true;
    const IS_SUPER_ADMIN = true;
    const IS_DISABLED = true;

    /** @var UserRepository */
    protected $userRepo;

    public function setUp()
    {
        $this->userRepo = new UserRepository();
    }

    public function tearDown()
    {
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function userCanBeSaved()
    {
        $user = new User();
        $user->setEmail(self::EMAIL);

        $this->userRepo->insert($user);

        $this->assertEquals(1, $user->getId());
    }

    /** @test save, getByID */
    public function userCanBeUpdated()
    {
        $user = new User();
        $user->setEmail(self::EMAIL);

        $this->userRepo->insert($user);

        $user->setEmail('test2@example.com');
        $this->userRepo->save($user);

        $sameUser = $this->userRepo->getById($user->getId());
        $this->assertEquals($user->getEmail(), $sameUser->getEmail());
    }

    /** @test getByID */
    public function gettingAnNonExistentUserById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->getById(1);
    }

    /** @test save */
    public function NonexistentUserCannotBeSaved()
    {
        $email = self::EMAIL;
        $user = new User();
        $user->setId(1);
        $user->setEmail($email);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->save($user);
    }

    /** @test getByEmail */
    public function userCanBeRetrievedByEmail()
    {
        $email = self::EMAIL;
        $user = new User();
        $user->setEmail($email);
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getByEmail($email);
        $this->assertEquals($user->getId(), $sameUser->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentUserByEmail_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->getByEmail(self::EMAIL);
    }


    /** @test emailExists */
    public function NonexistentUserCannotBeFoundByEmail()
    {
        $this->assertFalse($this->userRepo->emailExists(self::EMAIL));
    }


    /** @test facebookUIDExists */
    public function NonexistentUserCannotBeFoundByFacebookUID()
    {
        $this->assertFalse($this->userRepo->facebookUIDExists('randomUserUID'));
    }

    /** @test getByFacebookUID */
    public function gettingAnNonExistentUserByFacebookUID_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->getByFacebookUId('inexistentUID');
    }

    /** @test getByFacebookUID */
    public function userCanBeRetrievedByFacebookUID()
    {
        $facebookID = '10655gregbregh543';
        $user = new User();
        $user->setFacebookUID($facebookID);
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getByFacebookUId($facebookID);
        $this->assertEquals($user->getId(), $sameUser->getId());

        $this->assertTrue($this->userRepo->facebookUIDExists($facebookID));
    }

    /** @test googleUIDExists */
    public function NonexistentUserCannotBeFoundByGoogleUID()
    {
        $this->assertFalse($this->userRepo->googleUIDExists('randomUserUID'));
    }

    /** @test getByGoogleUID */
    public function gettingAnNonExistentUserByGoogleUID_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->getByGoogleUId('inexistentUID');
    }

    /** @test getByGoogleUID */
    public function userCanBeRetrievedByGoogleUID()
    {
        $googleUID = '10576';
        $user = new User();
        $user->setGoogleUID($googleUID);
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getByGoogleUId($googleUID);
        $this->assertEquals($user->getId(), $sameUser->getId());

        $this->assertTrue($this->userRepo->googleUIDExists($googleUID));
    }

    /** @test gitHubUIDExists */
    public function NonexistentUserCannotBeFoundByGitHubUID()
    {
        $this->assertFalse($this->userRepo->gitHubUIDExists('randomUserUID'));
    }

    /** @test getByGitHubUID */
    public function gettingAnNonExistentUserByGitHubUID_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->getByGitHubUId('inexistentUID');
    }

    /** @test getByGitHubUID */
    public function userCanBeRetrievedByGitHubUID()
    {
        $gitHubUID = '10576';
        $user = new User();
        $user->setGitHubUID($gitHubUID);
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getByGitHubUId($gitHubUID);
        $this->assertEquals($user->getId(), $sameUser->getId());

        $this->assertTrue($this->userRepo->gitHubUIDExists($gitHubUID));
    }

    /** @test twitterUIDExists */
    public function NonexistentUserCannotBeFoundByTwitterUID()
    {
        $this->assertFalse($this->userRepo->twitterUIDExists('randomUserUID'));
    }

    /** @test getByTwitterUID */
    public function gettingAnNonExistentUserByTwitterUID_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->getByTwitterUId('inexistentUID');
    }

    /** @test getByTwitterUID */
    public function userCanBeRetrievedByTwitterUID()
    {
        $twitterUID = '10576';
        $user = new User();
        $user->setTwitterUID($twitterUID);
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getByTwitterUId($twitterUID);
        $this->assertEquals($user->getId(), $sameUser->getId());

        $this->assertTrue($this->userRepo->twitterUIDExists($twitterUID));
    }

    /** @test profileURLExists */
    public function NonexistentUserCannotBeFoundByProfileURL()
    {
        $this->assertFalse($this->userRepo->profileURLExists('randomUserUID'));
    }

    /** @test getByProfileURL */
    public function gettingAnNonExistentUserByProfileURL_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->getByProfileURL('inexistentUID');
    }

    /** @test getByProfileURL */
    public function userCanBeRetrievedByProfileURL()
    {
        $profileURL = 'alecs.pandele';
        $user = new User();
        $user->setProfileURL($profileURL);
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getByProfileURL($profileURL);
        $this->assertEquals($user->getId(), $sameUser->getId());

        $this->assertTrue($this->userRepo->profileURLExists($profileURL));
    }

    /** @test getAll */
    public function getAllUsers()
    {
        $user = new User();
        $this->userRepo->insert($user);

        $this->assertCount(1, $this->userRepo->getAll());

        $user = new User();
        $this->userRepo->insert($user);

        $this->assertCount(2, $this->userRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function checkUserDetailsOnInsert()
    {
        $user = new User();
        $user->setEmail(self::EMAIL);
        $user->setShowEmail(self::SHOW_EMAIL);
        $user->setFirstName(self::FIRST_NAME);
        $user->setLastName(self::LAST_NAME);
        $user->setBio(self::BIO);
        $user->setCityName(self::CITY_NAME);
        $user->setCountryName(self::COUNTRY_NAME);
        $user->setJobTitle(self::JOB_TITLE);
        $user->setCompany(self::COMPANY);
        $user->setPassword(self::PASSWORD);
        $user->setFacebookUID(self::FACEBOOK_UID);
        $user->setGoogleUID(self::GOOGLE_UID);
        $user->setGitHubUID(self::GIT_HUB_UID);
        $user->setTwitterUID(self::TWITTER_UID);
        $user->setProfileURL(self::PROFILE_URL);
        $user->setProfilePic(self::PROFILE_PIC);
        $user->setIsAdmin(self::IS_ADMIN);
        $user->setIsSuperAdmin(self::IS_SUPER_ADMIN);
        $user->setIsDisabled(self::IS_DISABLED);
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getById($user->getId());
        $this->assertEquals(self::EMAIL, $sameUser->getEmail());
        $this->assertEquals(self::SHOW_EMAIL, $sameUser->canShowEmail());
        $this->assertEquals(self::FIRST_NAME, $sameUser->getFirstName());
        $this->assertEquals(self::LAST_NAME, $sameUser->getLastName());
        $this->assertEquals(self::BIO, $sameUser->getBio());
        $this->assertEquals(self::CITY_NAME, $sameUser->getCityName());
        $this->assertEquals(self::COUNTRY_NAME, $sameUser->getCountryName());
        $this->assertEquals(self::JOB_TITLE, $sameUser->getJobTitle());
        $this->assertEquals(self::COMPANY, $sameUser->getCompany());
        $this->assertTrue($sameUser->checkPassword(self::PASSWORD));
        $this->assertEquals(self::FACEBOOK_UID, $sameUser->getFacebookUID());
        $this->assertEquals(self::GOOGLE_UID, $sameUser->getGoogleUID());
        $this->assertEquals(self::GIT_HUB_UID, $sameUser->getGitHubUID());
        $this->assertEquals(self::TWITTER_UID, $sameUser->getTwitterUID());
        $this->assertEquals(self::PROFILE_URL, $sameUser->getProfileURL());
        $this->assertEquals(self::PROFILE_PIC, $sameUser->getProfilePic());
        $this->assertEquals(self::IS_ADMIN, $sameUser->isAdmin());
        $this->assertEquals(self::IS_SUPER_ADMIN, $sameUser->isSuperAdmin());
        $this->assertEquals(self::IS_DISABLED, $sameUser->isDisabled());
    }

    /** @test saveAsNew getById */
    public function checkUserDetailsOnUpdate()
    {
        $user = new User();
        $this->userRepo->insert($user);

        $user->setEmail(self::EMAIL);
        $user->setShowEmail(self::SHOW_EMAIL);
        $user->setFirstName(self::FIRST_NAME);
        $user->setLastName(self::LAST_NAME);
        $user->setBio(self::BIO);
        $user->setCityName(self::CITY_NAME);
        $user->setCountryName(self::COUNTRY_NAME);
        $user->setJobTitle(self::JOB_TITLE);
        $user->setCompany(self::COMPANY);
        $user->setPassword(self::PASSWORD);
        $user->setFacebookUID(self::FACEBOOK_UID);
        $user->setGoogleUID(self::GOOGLE_UID);
        $user->setGitHubUID(self::GIT_HUB_UID);
        $user->setTwitterUID(self::TWITTER_UID);
        $user->setProfileURL(self::PROFILE_URL);
        $user->setProfilePic(self::PROFILE_PIC);
        $user->setIsAdmin(self::IS_ADMIN);
        $user->setIsSuperAdmin(self::IS_SUPER_ADMIN);
        $user->setIsDisabled(self::IS_DISABLED);
        $this->userRepo->save($user);

        $sameUser = $this->userRepo->getById($user->getId());
        $this->assertEquals(self::EMAIL, $sameUser->getEmail());
        $this->assertEquals(self::SHOW_EMAIL, $sameUser->canShowEmail());
        $this->assertEquals(self::FIRST_NAME, $sameUser->getFirstName());
        $this->assertEquals(self::LAST_NAME, $sameUser->getLastName());
        $this->assertEquals(self::BIO, $sameUser->getBio());
        $this->assertEquals(self::CITY_NAME, $sameUser->getCityName());
        $this->assertEquals(self::COUNTRY_NAME, $sameUser->getCountryName());
        $this->assertEquals(self::JOB_TITLE, $sameUser->getJobTitle());
        $this->assertEquals(self::COMPANY, $sameUser->getCompany());
        $this->assertTrue($sameUser->checkPassword(self::PASSWORD));
        $this->assertEquals(self::FACEBOOK_UID, $sameUser->getFacebookUID());
        $this->assertEquals(self::GOOGLE_UID, $sameUser->getGoogleUID());
        $this->assertEquals(self::GIT_HUB_UID, $sameUser->getGitHubUID());
        $this->assertEquals(self::TWITTER_UID, $sameUser->getTwitterUID());
        $this->assertEquals(self::PROFILE_URL, $sameUser->getProfileURL());
        $this->assertEquals(self::PROFILE_PIC, $sameUser->getProfilePic());
        $this->assertEquals(self::IS_ADMIN, $sameUser->isAdmin());
        $this->assertEquals(self::IS_SUPER_ADMIN, $sameUser->isSuperAdmin());
        $this->assertEquals(self::IS_DISABLED, $sameUser->isDisabled());
    }
}