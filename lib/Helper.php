<?php

class Helper {
    public static function return_home_button(){
        include(DIR . 'theme/return_home.php');
    }

    public static function getDevice(){
        $detect = new Mobile_Detect();
        if($detect->isMobile()){
                $device = 'mobile';
        } else if($detect->isTablet()){
                $device = 'tablet';
        } else {
                $device = 'default';
        }
        //$device = 'mobile';
        return $device;
    }
}

