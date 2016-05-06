<?php

namespace DSI\Entity;

class User
{
    /** @var integer */
    private $id;

    /** @var string */
    private $firstName,
        $lastName,
        $email,
        $hashPassword,
        $bio,
        $location,
        $facebookUID,
        $googleUID,
        $gitHubUID,
        $twitterUID,
        $profileURL,
        $profilePic;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        if ($id <= 0)
            throw new \InvalidArgumentException('id: ' . $id);

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new \InvalidArgumentException('email: ' . $email);

        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->setHashPassword(
            password_hash($password, PASSWORD_BCRYPT)
        );
    }

    /**
     * @param string $hashPassword
     */
    public function setHashPassword(string $hashPassword)
    {
        $this->hashPassword = $hashPassword;
    }

    /**
     * @return string
     */
    public function getHashPassword(): string
    {
        return (string)$this->hashPassword;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return (bool)password_verify($password, $this->hashPassword);
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return (string)$this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return (string)$this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
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
    public function getFacebookUID(): string
    {
        return (string)$this->facebookUID;
    }

    /**
     * @param int $facebookUID
     */
    public function setFacebookUID(string $facebookUID)
    {
        if (trim($facebookUID) == '')
            throw new \InvalidArgumentException('facebookUId: ' . $facebookUID);

        $this->facebookUID = $facebookUID;
    }

    public function getGoogleUID(): string
    {
        return (string)$this->googleUID;
    }

    public function setGoogleUID(string $googleUID)
    {
        if (trim($googleUID) == '')
            throw new \InvalidArgumentException('googleUId: ' . $googleUID);

        $this->googleUID = $googleUID;
    }

    public function getGitHubUID(): string
    {
        return (string)$this->gitHubUID;
    }

    public function setGitHubUID(string $gitHubUID)
    {
        if (trim($gitHubUID) == '')
            throw new \InvalidArgumentException('githubUId: ' . $gitHubUID);

        $this->gitHubUID = $gitHubUID;
    }

    public function getTwitterUID(): string
    {
        return (string)$this->twitterUID;
    }

    public function setTwitterUID(string $twitterUID)
    {
        if (trim($twitterUID) == '')
            throw new \InvalidArgumentException('twitterUId: ' . $twitterUID);

        $this->twitterUID = $twitterUID;
    }

    /**
     * @return string
     */
    public function getBio(): string
    {
        return (string)$this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio(string $bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return (string)$this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getProfilePic(): string
    {
        return (string)$this->profilePic;
    }

    /**
     * @return string
     */
    public function getProfilePicOrDefault(): string
    {
        return (string) ($this->profilePic ?? '0.svg');
    }

    /**
     * @param string $profilePic
     */
    public function setProfilePic(string $profilePic)
    {
        $this->profilePic = $profilePic;
    }
}
