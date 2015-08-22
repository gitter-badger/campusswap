<?php

namespace Campusswap\Exception;

class AuthException extends \Exception {
    public $instruction;
    
    public function __construct($message, $instructions = "Please Authenticate prior to viewing this page.") {
        parent::__construct($message, $code, $previous);
        $this->instruction = $instructions;
    }
    
    public function setInstruction($inst) {
        $this->instruction = $inst;
    }
    
    public function getInstruction() {
        return $this->instruction;
    }
}
