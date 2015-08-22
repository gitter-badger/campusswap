<?php

namespace Campusswap\Object;

class Access {

    private $id;
    private $ip;
    private $usernames;
    private $status;
    private $visits;
    private $failed_logins;
    private $intrusions;
    private $datetime;

    public function hasUser($fullName) {
        $access_usernames = explode("/", $this->getUsernames());
        if(in_array($fullName, $access_usernames)){
            return true;
        } else {
            return false;
        }
    }
        
    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getFailedLogins()
    {
        return $this->failed_logins;
    }

    /**
     * @param mixed $failed_logins
     */
    public function setFailedLogins($failed_logins)
    {
        $this->failed_logins = $failed_logins;
    }
    
    /**
     * @return mixed
     */
    public function getIntrusions()
    {
        return $this->intrusions;
    }

    /**
     * @param mixed $intrusions
     */
    public function setIntrusions($intrusions)
    {
        $this->$intrusions = $intrusions;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUsernames()
    {
        return $this->usernames;
    }

    /**
     * @param mixed $usernames
     */
    public function setUsernames($usernames)
    {
        $this->usernames = $usernames;
    }

    /**
     * @return mixed
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * @param mixed $visits
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;
    }
}

?>