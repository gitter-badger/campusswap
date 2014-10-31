<?php

class vers {
	
	public static $conn;
        
        private $id;
        private $ver;
        private $username;
        private $domain;
        private $type;
        
        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getVer() {
            return $this->ver;
        }

        public function setVer($ver) {
            $this->ver = $ver;
        }

        public function getUsername() {
            return $this->username;
        }

        public function setUsername($username) {
            $this->username = $username;
        }

        public function getDomain() {
            return $this->domain;
        }

        public function setDomain($domain) {
            $this->domain = $domain;
        }

        public function getType() {
            return $this->type;
        }

        public function setType($type) {
            $this->type = $type;
        }

        public static function deleteVer($key, $conn){
            
            $sql = "DELETE FROM vers WHERE ver='$key'";
                    
            $return = mysqli_query($conn, $sql);
            
            if($return){
                return TRUE;
            } else {
                return FALSE;
            }
            
        }
        
        public function getVerification($ver, $conn){
            
            $query = "SELECT * FROM vers WHERE ver = '" . $ver . "' ";
            
            try {
               $result = mysqli_query($conn, $query); 
               
               $row = mysqli_fetch_array($result, MYSQLI_BOTH);
               
               return $row;
               
            } catch(mysqli_sql_exception $ex){
                echo 'Mysql Error ' . $ex->getMessage() . '<br />';
            }
            
        }        
        
        public static function createVer($ver, $user, $domain, $type, $conn) {
            
            $query = "INSERT INTO vers (ver, username, domain, type) ";
            $query = $query . " VALUES ('" . $ver . "', '" . $user . "', '" . $domain . "', '" . $type . "') ";
            
            
            try {
                $result = mysqli_query($conn, $query);
                                  
            } catch(mysqli_sql_exception $ex){
                echo 'Mysql Error ' . $ex->getMessage() . '<br />';
                
            }
            
            
            
            if($result) {
                return TRUE;
            } else {
                RETURN FALSE;
            }
        }
	
	public static function getVerFromUser($user, $domain, $conn){
		
		$query = mysqli_query($conn, 
                        "SELECT ver 
                         FROM vers 
                         WHERE username = '" . $user . "'
                         AND domain = '" . $domain . "'");
		
		if($query){
			$query = mysqli_fetch_array($query, MYSQLI_ASSOC);
                        return $query['ver'];
		} else {
			return FALSE;
		}
		
	}
        
        public static function verSent($user, $domain, $conn){
            
            $query = mysqli_query($conn, 
                        "SELECT ver 
                         FROM vers 
                         WHERE username = '" . $user . "'
                         AND domain = '" . $domain . "'");
            
            if(mysqli_num_rows($query) > 0){
                return TRUE;
            } else {
                return FALSE;
            }
            
        }
	
}


?>