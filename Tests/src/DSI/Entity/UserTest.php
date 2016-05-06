<?php

use DSI\Entity\User;

require_once __DIR__ . '/../../../config.php';

class UserTest extends \PHPUnit_Framework_TestCase
{
    /** @var User */
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->user->setId(1);
        $this->assertEquals(1, $this->user->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->user->setId(0);
    }

    /** @test setEmail, getEmail */
    public function settingAnEmail_returnsTheEmail()
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
    }

    /** @test setFirstName, getFirstName */
    public function settingFirstName_returnsFirstName()
    {
        $firstName = 'Alexandru';
        $this->user->setFirstName($firstName);
        $this->assertEquals($firstName, $this->user->getFirstName());
    }

    /** @test setLastName, getLastName */
    public function settingLastName_returnsLastName()
    {
        $lastName = 'Pandele';
        $this->user->setLastName($lastName);
        $this->assertEquals($lastName, $this->user->getLastName());
    }

    /** @test setProfileURL, getProfileURL */
    public function settingProfileURL_returnsProfileURL()
    {
        $profileURL = 'alecs.pandele';
        $this->user->setProfileURL($profileURL);
        $this->assertEquals($profileURL, $this->user->getProfileURL());
    }

    /** @test setId */
    public function settingAnInvalidEmail_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->user->setEmail('test');
    }

    /** @test setPassword, checkPassword */
    public function settingAPassword_verifiesThePassword()
    {
        $password = 'myOwnPassword';
        $this->user->setPassword($password);
        $this->assertTrue($this->user->checkPassword($password));
    }

    /** @test setFacebookID, getFacebookID */
    public function settingFacebookID_getsFacebookID()
    {
        $facebookID = '1087274589';
        $this->user->setFacebookUID($facebookID);
        $this->assertEquals($facebookID, $this->user->getFacebookUID());
    }

    /** @test setFacebookId */
    public function settingAnInvalidFacebookId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->user->setFacebookUID('');
    }

    /** @test setGoogleID, getGoogleID */
    public function settingGoogleID_getsGoogleID()
    {
        $GoogleID = '1087274589';
        $this->user->setGoogleUID($GoogleID);
        $this->assertEquals($GoogleID, $this->user->getGoogleUID());
    }

    /** @test setGoogleId */
    public function settingAnInvalidGoogleId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->user->setGoogleUID('');
    }

    /** @test setGitHubID, getGitHubID */
    public function settingGitHubID_getsGitHubID()
    {
        $GitHubID = '1087274589';
        $this->user->setGitHubUID($GitHubID);
        $this->assertEquals($GitHubID, $this->user->getGitHubUID());
    }

    /** @test setGitHubId */
    public function settingAnInvalidGitHubId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->user->setGitHubUID('');
    }

    /** @test setTwitterID, getTwitterID */
    public function settingTwitterID_getsTwitterID()
    {
        $TwitterID = '1087274589';
        $this->user->setTwitterUID($TwitterID);
        $this->assertEquals($TwitterID, $this->user->getTwitterUID());
    }

    /** @test setTwitterId */
    public function settingAnInvalidTwitterId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->user->setTwitterUID('');
    }

    /** @test setBio, getBio */
    public function settingBio_getsBio()
    {
        $bio = 'My Wonderful Bio';
        $this->user->setBio($bio);
        $this->assertEquals($bio, $this->user->getBio());
    }

    /** @test setLocation, getLocation */
    public function settingLocation_getsLocation()
    {
        $location = 'MyHomeTown';
        $this->user->setLocation($location);
        $this->assertEquals($location, $this->user->getLocation());
    }

    /** @test setProfilePic, getProfilePic */
    public function settingProfilePic_getsProfilePic()
    {
        $profilePic = 'myImage.jpg';
        $this->user->setProfilePic($profilePic);
        $this->assertEquals($profilePic, $this->user->getProfilePic());
    }
}