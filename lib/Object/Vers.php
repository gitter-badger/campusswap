<?php
namespace Campusswap\Object;

class Vers {

    private $id;
    private $ver;
    private $username;
    private $domain;
    private $type;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getVer()
    {
        return $this->ver;
    }

    public function setVer($ver)
    {
        $this->ver = $ver;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

    }

}