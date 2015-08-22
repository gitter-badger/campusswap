<?php

# Vasilios C. Kaloidis

namespace Campusswap\Util;

class Timer
{
    private $start_time = NULL;
    private $end_time = NULL;
    public static $last_time = NULL;

    public $log;

    public function __construct($logInput) {
        $this->log = $logInput;
    }

    private function getmicrotime(){
      list($usec, $sec) = explode(" ", microtime());
      return ((float)$usec + (float)$sec);
    }

    function start(){
      //$this->start_time = $this->getmicrotime();
        $this->start_time = microtime(true);
    }

    /**
     * @param string $timed_object the name of the timed object for logging purposes
     * @return float|null Logs the timer time and the timed object name as an 'info' and stops then resets the timer
     */
    function stop($timed_object = 'Generic Timer'){
        self::$last_time = NULL;
//        $this->end_time = $this->getmicrotime();

        $this->end_time = microtime(true);

//      We need to fix the bug when logging from classes and modules before we can log in here
//      $this->log->log('USER'. 'info', 'TimerUtil: ' . $timed_object . ' - ' . $this->result());

        self::$last_time =  sprintf('%f', ($this->end_time - $this->start_time));
        $this->clearTime();
        return self::$last_time;

    }

    function result(){
        if (is_null($this->start_time))
        {
            exit('Timer: start method not called !');
            return false;
        }
        else if (is_null($this->end_time))
        {
            exit('Timer: stop method not called !');
            return false;
        }


        return self::$last_time;
    }

    # an alias of result function
    function time(){
        $this->result();
    }
    
    function clearTime() {
        $this->start_time = null;
        $this->end_time = null;
    }

}


?>
