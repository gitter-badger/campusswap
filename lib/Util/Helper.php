<?php
namespace Campusswap\Util;

class Helper {

    /* http://stackoverflow.com/questions/25800310/reduce-image-size-while-uploading-using-the-following-php-code-used-to-upload-im */
    public function resizeImgage($img, $source, $dest, $maxw, $maxh ) {      

        if( $img ) {
            list( $width, $height  ) = getimagesize( $jpg ); //$type will return the type of the image
            $source = imagecreatefromjpeg( $jpg );

            if( $maxw >= $width && $maxh >= $height ) {
                $ratio = 1;
            }elseif( $width > $height ) {
                $ratio = $maxw / $width;
            }else {
                $ratio = $maxh / $height;
            }

            $thumb_width = round( $width * $ratio ); //get the smaller value from cal # floor()
            $thumb_height = round( $height * $ratio );

            $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
            imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height );

            $path = $dest.$img."_thumb.jpg";
            imagejpeg( $thumb, $path, 75 );
        } else {
//            throw new Exception("")
        }
        imagedestroy( $thumb );
        imagedestroy( $source );
    }
    
    public function obfuscate_username($username, $domain = null) {
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
    
    public function print_error_dump($e) {
        $Helper->print_error("<b>Message:</b><br>" . $e->getMessage()); 
        echo "<br>";
        //$Helper->print_error("<b>File - Line</b><br>" . $e->getFile() . " - " . + $e->getLine());
        //echo "<br>";
        //$Helper->print_error("<b>Trace</b><br>" . $e->getTrace());
        //echo "<br>";
        //$Helper->print_error("<b>Trace (String)</b><br>" . $e->getTraceAsString());
        //echo "<br>";
        //$Helper->print_error("<b>Trace (String)</b><br>" . $e->getCode());
    }
    
    public function print_alert($alert, $message) {
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

    /**
     * 
     * Prints an error message in a red error box
     * 
     * @param type $message message to print in red error box
     */
    public function print_error($message = false) {
        if($message) {
            self::print_alert("danger", $message);
        } else {
            $message = "There was an error loading this page, we apoligize for the inconvenience and are working quickly to fix this problem";
            self::print_alert ($alert, $message);
        }
    }
    
    /**
     * Prints a message in yellow message box
     * 
     * @param type $message message to print in yellow warning box
     */
    public function print_warning($message) {
         self::print_alert("warning", $message);
    }
    
    /**
     * Prints a blue message box
     * 
     * @param type $message to output in Info message box
     */
    public function print_info($message) {
         self::print_alert("danger", $message);
    }
    
    /**
     * Prints a green box with the input
     * 
     * @param type $message to print in the green message box
     */
    public function print_message($message) {
        self::print_alert("success", $message);
    }

    public function close_lightwindow_button() {
        echo '<a href="#" id="lw_close_button_override lightwindow_title_bar_close_link" class="btn btn-success">Close!</a>';
    }

    public function contact_support_button($center = null){
        echo '<a class="center" href="' . URL . 'contact.php">';
            echo '<button type="button" class="btn btn-success">Contact</button>';
        echo '</a>';
    }

    public function return_home_button($text = null, $size = null){
        echo '<br><a class="center" href="' . $Config->get("url") . '">';

        if($size != null){
            echo '<button class="btn btn-lg btn-success btn-block" type="submit">';
        } else {
            echo '<button type="button" class="btn btn-success">';
        }

        if($text != null){
            echo $text . '</button>';
        } else {
            echo 'Return Home</button>';
        }

        echo '</a>';
    }

    public function getDevice(){
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

