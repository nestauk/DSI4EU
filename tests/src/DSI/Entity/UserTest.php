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

    /** @test */
    public function settingName_canGetFullName()
    {
        $this->user->setFirstName($firstName = 'Alecs');
        $this->user->setLastName($lastName = 'Viper');
        $this->assertEquals($firstName . ' ' . $lastName, $this->user->getFullName());
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

    /** @test */
    public function settingCityName_getsCityName()
    {
        $cityName = 'MyHomeTown';
        $this->user->setCityName($cityName);
        $this->assertEquals($cityName, $this->user->getCityName());
    }

    /** @test */
    public function settingCountryName_getsCountryName()
    {
        $countryName = 'Romania';
        $this->user->setCountryName($countryName);
        $this->assertEquals($countryName, $this->user->getCountryName());
    }

    /** @test setProfilePic, getProfilePic */
    public function settingProfilePic_getsProfilePic()
    {
        $this->assertEquals('', $this->user->getProfilePic());

        $profilePic = 'myImage.jpg';
        $this->user->setProfilePic($profilePic);
        $this->assertEquals($profilePic, $this->user->getProfilePic());
    }

    /** @test */
    public function settingProfilePic_canGetProfilePicOrDefault()
    {
        $this->assertEquals(User::DEFAULT_PROFILE_PIC, $this->user->getProfilePicOrDefault());

        $profilePic = 'myImage.jpg';
        $this->user->setProfilePic($profilePic);
        $this->assertEquals($profilePic, $this->user->getProfilePicOrDefault());
    }

    /** @test setJobTitle, getJobTitle */
    public function settingJobTitle_getsJobTitle()
    {
        $jobTitle = 'WebDev at Inoveb';
        $this->user->setJobTitle($jobTitle);
        $this->assertEquals($jobTitle, $this->user->getJobTitle());
    }

    /** @test setCompany, getCompany */
    public function settingCompany_getsCompany()
    {
        $company = 'Inoveb';
        $this->user->setCompany($company);
        $this->assertEquals($company, $this->user->getCompany());
    }

    /** @test setShowEmail, getShowEmail */
    public function settingShowEmail_getsShowEmail()
    {
        $this->user->setShowEmail(true);
        $this->assertTrue($this->user->canShowEmail());
        $this->user->setShowEmail(false);
        $this->assertFalse($this->user->canShowEmail());
    }

    /** @test */
    public function settingIsDisabled_getsIsDisabled()
    {
        $this->user->setDisabled(true);
        $this->assertTrue($this->user->isDisabled());
        $this->user->setDisabled(false);
        $this->assertFalse($this->user->isDisabled());
    }

    /** @test */
    public function settingRole_getsRole()
    {
        $this->user->setRole($role = 'Sys Admin');
        $this->assertEquals($role, $this->user->getRole());
    }

    /** @test */
    public function canCheckIfUserIsSysAdmin()
    {
        $this->user->setRole($role = 'sys-admin');
        $this->assertEquals(true, $this->user->isSysAdmin());

        $this->user->setRole($role = 'anything-else');
        $this->assertEquals(false, $this->user->isSysAdmin());
    }

    /** @test */
    public function canCheckIfUserIsCommunityAdmin()
    {
        $this->user->setRole($role = 'sys-admin');
        $this->assertEquals(true, $this->user->isCommunityAdmin());

        $this->user->setRole($role = 'community-admin');
        $this->assertEquals(true, $this->user->isCommunityAdmin());

        $this->user->setRole($role = 'anything-else');
        $this->assertEquals(false, $this->user->isCommunityAdmin());
    }

    /** @test */
    public function canCheckIfUserIsEditorialAdmin()
    {
        $this->user->setRole($role = 'sys-admin');
        $this->assertEquals(true, $this->user->isEditorialAdmin());

        $this->user->setRole($role = 'editorial-admin');
        $this->assertEquals(true, $this->user->isEditorialAdmin());

        $this->user->setRole($role = 'anything-else');
        $this->assertEquals(false, $this->user->isEditorialAdmin());
    }
}