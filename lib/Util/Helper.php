<?php

class Helper {
    public static function obfuscate_username($username, $domain = null) {
        if($domain == null) {
            $explode = explode("@", $username);
            $domain = $explode[1];
            $username = $explode[0];
        }
        $return = '';
        $strlen = strlen($username);
        $first_letter = str_split($username);

        $return .= $first_letter[0];

        for($i = 0; $i < ($strlen - 1); $i++){ //ECHO USERNAME, WITH ***'S
            $return .= "*";
        }
        $return .= '@' . $domain;

        return $return;
    }

    public static function print_alert($alert, $message) {
        if($alert == 'danger') {
            echo '<div class="alert alert-danger">';
        } else if($alert =='warning') {
            echo '<div class="alert alert-warning">';
        } else if($alert == 'info') {
            echo '<div class="alert alert-info">';
        } else if($alert =='success') {
            echo '<div class="alert alert-success">';
        }

        echo $message;

        echo '</div>';
    }

    public static function close_lightwindow_button() {
        echo '<a href="#" id="lw_close_button_override lightwindow_title_bar_close_link" class="btn btn-success">Close!</a>';
    }

    public static function contact_support_button($center = null){
        echo '<a class="center" href="' . $url . 'contact.php">';
            echo '<button type="button" class="btn btn-success">Contact</button>';
        echo '</a>';
    }

    public static function return_home_button(){
        include(DIR . 'interface/return_home.php');
    }

    public static function getDevice(){
       // TODO: Break this stuff out to its own mobile helper class

//      $detect = new Mobile_Detect();
//      $is_mobile = $detect->isMobile();
//      $is_tablet = $detect->isTablet();

        $is_mobile = false;
        $is_tablet = false;
        if($is_mobile){
                $device = 'mobile';
        } else if($is_tablet){
                $device = 'tablet';
        } else {
                $device = 'default';
        }
        //$device = 'mobile';
        return $device;
    }
}

