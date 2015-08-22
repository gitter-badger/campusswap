<?php
namespace Campusswap\Exception;

class IntrusionException extends \Exception {
    public $user;
    public $ip;
    public $script;
    
    public function __construct($intrusion, $script = 'script', $user = '') {
        parent::__construct($intrusion, $code, $previous);
                
        $this->ip = $_SERVER['REMOTE_ADDR'];
        
        if(strcasecmp($script, 'script')==0) { 
            $this->script = $_SERVER['SCRIPT_NAME'];
        } else {
            $this->script = $script;
        }
    }
    
    public function setUser($userInp) {
       $this->user = $userInp;
    }
    
    public function setIntrusion($intrusion) {
        $this->message = $intrusion;
    }
    
    public function getIp() {
        return $this->ip;
    }
    
    public function getUser() {
        return $this->user; 
    }

    public function getIntrusion() {
        return $this->message;
    }
    
}
