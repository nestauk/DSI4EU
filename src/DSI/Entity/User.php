<?php

namespace DSI\Entity;

class User
{
    const DEFAULT_PROFILE_PIC = '0.svg';
    /** @var integer */
    private $id;

    /** @var string */
    private $firstName,
        $lastName,
        $email,
        $hashPassword,
        $bio,
        $cityName,
        $countryName,
        $jobTitle,
        $company,
        $facebookUID,
        $googleUID,
        $gitHubUID,
        $twitterUID,
        $profileURL,
        $profilePic;

    /** @var bool */
    private $showEmail;

    /** @var bool */
    private $isAdmin, $isSuperAdmin;

    /** @var bool */
    private $isDisabled;

    /** @var string */
    private $role;

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        if ($id <= 0)
            throw new \InvalidArgumentException('id: ' . $id);

        $this->id = (int)$id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return (string)$this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new \InvalidArgumentException('email: ' . $email);

        $this->email = (string)$email;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->setHashPassword(
            password_hash($password, PASSWORD_BCRYPT)
        );
    }

    /**
     * @param string $hashPassword
     */
    public function setHashPassword($hashPassword)
    {
        $this->hashPassword = $hashPassword;
    }

    /**
     * @return string
     */
    public function getHashPassword()
    {
        return (string)$this->hashPassword;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return (bool)password_verify($password, $this->hashPassword);
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return (string)$this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return (string)$this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * @return string
     */
    public function getProfileURL()
    {
        return $this->profileURL;
    }

    /**
     * @param string $profileURL
     */
    public function setProfileURL($profileURL)
    {
        $this->profileURL = $profileURL;
    }

    /**
     * @return int
     */
    public function getFacebookUID()
    {
        return (string)$this->facebookUID;
    }

    /**
     * @param int $facebookUID
     */
    public function setFacebookUID($facebookUID)
    {
        if (trim($facebookUID) == '')
            throw new \InvalidArgumentException('facebookUId: ' . $facebookUID);

        $this->facebookUID = $facebookUID;
    }

    public function getGoogleUID()
    {
        return (string)$this->googleUID;
    }

    public function setGoogleUID($googleUID)
    {
        if (trim($googleUID) == '')
            throw new \InvalidArgumentException('googleUId: ' . $googleUID);

        $this->googleUID = $googleUID;
    }

    public function getGitHubUID()
    {
        return (string)$this->gitHubUID;
    }

    public function setGitHubUID($gitHubUID)
    {
        if (trim($gitHubUID) == '')
            throw new \InvalidArgumentException('githubUId: ' . $gitHubUID);

        $this->gitHubUID = $gitHubUID;
    }

    public function getTwitterUID()
    {
        return (string)$this->twitterUID;
    }

    public function setTwitterUID($twitterUID)
    {
        if (trim($twitterUID) == '')
            throw new \InvalidArgumentException('twitterUId: ' . $twitterUID);

        $this->twitterUID = $twitterUID;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return (string)$this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio($bio)
    {
        $this->bio = (string)$bio;
    }

    /**
     * @return string
     */
    public function getCityName()
    {
        return (string)$this->cityName;
    }

    /**
     * @param string $cityName
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return (string)$this->countryName;
    }

    /**
     * @param string $countryName
     */
    public function setCountryName($countryName)
    {
        $this->countryName = (string)$countryName;
    }

    /**
     * @return string
     */
    public function getProfilePic()
    {
        return (string)$this->profilePic;
    }

    /**
     * @return string
     */
    public function getProfilePicOrDefault()
    {
        return (string)($this->profilePic ?? self::DEFAULT_PROFILE_PIC);
    }

    /**
     * @param string $profilePic
     */
    public function setProfilePic($profilePic)
    {
        $this->profilePic = $profilePic;
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return (string)$this->jobTitle;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = (string)$jobTitle;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return (string)$this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = (string)$company;
    }

    /**
     * @return boolean
     */
    public function canShowEmail()
    {
        return (bool)$this->showEmail;
    }

    /**
     * @param boolean $showEmail
     */
    public function setShowEmail($showEmail)
    {
        $this->showEmail = (bool)$showEmail;
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return (bool)$this->isAdmin;
    }

    /**
     * @param boolean $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = (bool)$isAdmin;
    }

    /**
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return (bool)$this->isSuperAdmin;
    }

    /**
     * @param boolean $isSuperAdmin
     */
    public function setIsSuperAdmin($isSuperAdmin)
    {
        $this->isSuperAdmin = (bool)$isSuperAdmin;
    }

    /**
     * @return boolean
     */
    public function isDisabled()
    {
        return (bool)$this->isDisabled;
    }

    /**
     * @param boolean $isDisabled
     */
    public function setIsDisabled($isDisabled)
    {
        $this->isDisabled = (bool)$isDisabled;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return (string)$this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role)
    {
        $this->role = (string)$role;
    }

    public function isSysAdmin()
    {
        return $this->role == 'sys-admin';
    }

    public function isCommunityAdmin()
    {
        return $this->isSysAdmin() OR $this->role == 'community-admin';
    }

    public function isEditorialAdmin()
    {
        return $this->isSysAdmin() OR $this->role == 'editorial-admin';
    }
}