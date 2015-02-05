<?php
/**
 * User: vaskaloidis
 * Date: 11/6/14
 * Time: 2:02 AM
 */

class Authentication {

    private $isLi;
    private $liId;
    private $liUser;
    private $liDomain;
    private $liFullName;
    private $liIsAdmin;
    private $liLevel;

    /**
     * @return mixed
     */
    public function getIsLi()
    {
        return $this->isLi;
    }

    /**
     * @param mixed $isLi
     */
    public function setIsLi($isLi)
    {
        $this->isLi = $isLi;
    }

    /**
     * @return mixed
     */
    public function getLiDomain()
    {
        return $this->liDomain;
    }

    /**
     * @param mixed $liDomain
     */
    public function setLiDomain($liDomain)
    {
        $this->liDomain = $liDomain;
    }

    /**
     * @return mixed
     */
    public function getLiFullName()
    {
        return $this->liFullName;
    }

    /**
     * @param mixed $liFullName
     */
    public function setLiFullName($liFullName)
    {
        $this->liFullName = $liFullName;
    }

    /**
     * @return mixed
     */
    public function getLiId()
    {
        return $this->liId;
    }

    /**
     * @param mixed $liId
     */
    public function setLiId($liId)
    {
        $this->liId = $liId;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->liIsAdmin;
    }

    /**
     * @param mixed $liIsAdmin
     */
    public function setIsAdmin($liIsAdmin)
    {
        $this->liIsAdmin = $liIsAdmin;
    }

    /**
     * @return mixed
     */
    public function getLiLevel()
    {
        return $this->liLevel;
    }

    /**
     * @param mixed $liLevel
     */
    public function setLiLevel($liLevel)
    {
        $this->liLevel = $liLevel;
    }

    /**
     * @return mixed
     */
    public function getLiUser()
    {
        return $this->liUser;
    }

    /**
     * @param mixed $liUser
     */
    public function setLiUser($liUser)
    {
        $this->liUser = $liUser;
    }



} 