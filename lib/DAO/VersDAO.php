<?php

class VersDAO {
	
	public static $conn, $config, $log, $count, $sql;
        
        private $id;
        private $ver;
        private $username;
        private $domain;
        private $type;

    public function __construct($connection, $config, $log) {
        self::$conn = $connection;
        self::$config = $config;
        self::$log = $log;
        $dir = Config::get('dir');
        include $dir . 'lib/Objects/Vers.php';
    }

    private function createObject($sql){

        $result = mysqli_query(self::$conn, $sql);

        self::$sql = $sql;

        self::$count = mysqli_num_rows($result);

        if(self::$count == 1){
            while($row = mysqli_fetch_array($result)){
                $Vers = new Vers();
                $Vers->setId($row['id']);
                $Vers->setVer($row['ver']);
                $Vers->setDomain($row['domain']);
                $Vers->setUsername($row['username']);
                $Vers->setType($row['type']);
            }
            return $Vers;
        } else {
            return false;
        }
    }

        public function deleteVer($key){
            
            $sql = "DELETE FROM vers WHERE ver='$key'";
                    
            $return = mysqli_query(self::$conn, $sql);
            
            if($return){
                return TRUE;
            } else {
                return FALSE;
            }
            
        }
        
        public function getVerification($ver){
            
            $sql = "SELECT * FROM vers WHERE ver = '" . $ver . "' ";

            return $this->createObject($sql);
            
        }        
        
        public function createVer($ver, $user, $domain, $type) {
            
            $query = "INSERT INTO vers (ver, username, domain, type) ";
            $query = $query . " VALUES ('" . $ver . "', '" . $user . "', '" . $domain . "', '" . $type . "') ";

            try {
                $result = mysqli_query(self::$conn, $query);
                                  
            } catch(mysqli_sql_exception $ex){
                echo 'Mysql Error ' . $ex->getMessage() . '<br />';
            }

            if($result) {
                return TRUE;
            } else {
                RETURN FALSE;
            }
        }
	
	public function getVerFromUser($user, $domain){
		
		$sql = "SELECT *
                     FROM vers
                     WHERE username = '" . $user . "'
                     AND domain = '" . $domain . "'";

        $return = $this->createObject($sql);

		if($return){
            return $return->getVer();
		} else {
			return FALSE;
		}
		
	}

    public function verSent($username, $domain) {
        $sql = "SELECT * FROM vers WHERE username = '" . $username . "' and domain = '" . $domain . "'";

        $return = $this->createObject($sql);

        if($return) {
            return true;
        } else {
            return false;
        }
    }
	
}


?>