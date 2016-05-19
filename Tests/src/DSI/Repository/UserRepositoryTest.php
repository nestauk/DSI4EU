<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Entity\User;

class UserRepositoryTest extends PHPUnit_Framework_TestCase
{
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
        $user->setEmail('test@example.com');

        $this->userRepo->insert($user);

        $this->assertEquals(1, $user->getId());
    }

    /** @test save, getByID */
    public function userCanBeUpdated()
    {
        $user = new User();
        $user->setEmail('test@example.com');

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
        $email = 'test@example.com';
        $user = new User();
        $user->setId(1);
        $user->setEmail($email);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->userRepo->save($user);
    }

    /** @test getByEmail */
    public function userCanBeRetrievedByEmail()
    {
        $email = 'test@example.com';
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
        $this->userRepo->getByEmail('test@example.com');
    }


    /** @test emailExists */
    public function NonexistentUserCannotBeFoundByEmail()
    {
        $this->assertFalse($this->userRepo->emailExists('test@example.com'));
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
    public function setAllUsersDetails()
    {
        $user = new User();
        $user->setEmail($email = 'test@example.com');
        $user->setFirstName($firstName = 'firstName');
        $user->setLastName($lastName = 'lastName');
        $user->setBio($bio = 'My Own Personal Bio');
        $user->setLocation($location = 'London, UK');
        $user->setJobTitle($jobTitle = 'WebDev at Inoveb');
        $user->setPassword($password = 'password');
        $user->setFacebookUID($facebookUID = 'facebookUID');
        $user->setGoogleUID($googleUID = 'googleUID');
        $user->setGitHubUID($gitHubUID = 'gitHubUID');
        $user->setTwitterUID($twitterUID = 'twitterUID');
        $user->setProfileURL($profileUrl = 'profileURL');
        $user->setProfilePic($profilePic = 'profilePic.jpg');
        $this->userRepo->insert($user);

        $sameUser = $this->userRepo->getById($user->getId());
        $this->assertEquals($email, $sameUser->getEmail());
        $this->assertEquals($firstName, $sameUser->getFirstName());
        $this->assertEquals($lastName, $sameUser->getLastName());
        $this->assertEquals($bio, $sameUser->getBio());
        $this->assertEquals($location, $sameUser->getLocation());
        $this->assertEquals($jobTitle, $sameUser->getJobTitle());
        $this->assertTrue($sameUser->checkPassword($password));
        $this->assertEquals($facebookUID, $sameUser->getFacebookUID());
        $this->assertEquals($googleUID, $sameUser->getGoogleUID());
        $this->assertEquals($gitHubUID, $sameUser->getGitHubUID());
        $this->assertEquals($twitterUID, $sameUser->getTwitterUID());
        $this->assertEquals($profileUrl, $sameUser->getProfileURL());
        $this->assertEquals($profilePic, $sameUser->getProfilePic());
    }
}