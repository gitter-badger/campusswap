<?php

namespace Campusswap\Object;

class Domain {

    private $id;
    private $name;
    private $domain;


    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getDomain(){
        return $this->domain;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setDomain($domain){
        $this->domain = $domain;
    }
	
	
}


?>