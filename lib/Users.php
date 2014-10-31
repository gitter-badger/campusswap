<?php

class users {
	
	public static $conn;
	
	private $id;
	private $username;
	private $domain;
	private $password;
	private $level;
	private $likes;
	private $created;
	private $modified;
	private $status;
        
        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
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

        public function getPassword() {
            return $this->password;
        }

        public function setPassword($password) {
            $this->password = $password;
        }

        public function getLevel() {
            return $this->level;
        }

        public function setLevel($level) {
            $this->level = $level;
        }

        public function getLikes() {
            return $this->likes;
        }

        public function setLikes($likes) {
            $this->likes = $likes;
        }

        public function getCreated() {
            return $this->created;
        }

        public function setCreated($created) {
            $this->created = $created;
        }

        public function getModified() {
            return $this->modified;
        }

        public function setModified($modified) {
            $this->modified = $modified;
        }

        public function getStatus() {
            return $this->status;
        }

        public function setStatus($status) {
            $this->status = $status;
        }

        function Users($id, $conn){
            $sql = "SELECT * FROM users WHERE id = '" . $id . "'";
            $result = mysqli_query($conn, $sql);
                        
            $row = mysqli_fetch_assoc($result);

            $this->setId($row['id']);
            $this->setUsername($row['username']);
            $this->setDomain($row['domain']);
            $this->setLevel($row['level']);
            $this->setLikes($row['likes']);
            $this->setCreated($row['created']);
            $this->setModified($row['modified']);
            $this->setStatus($row['status']);
        }
        	
        public function doesUserLike($id) {

            $likes = explode("/", $this->getLikes());
            
            if(in_array($id, $likes)){
                return true;
            } else {
                return false;
            }
            
        }
        
        //CREATE USER
        public static function createUser($user, $domain, $password, $conn){
            
            $sql = "INSERT INTO users 
                        (id, username, password, domain, level, created, modified) 
                    VALUES 
                        (NULL, '$user', SHA('$password'), '$domain', 'normal', NOW(), NOW())";

            $result = mysqli_query($conn, $sql);
            
            if($result){
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        //SEE IF USER EXISTS ALREADY
	public static function userExists($user, $domain, $conn){
		
		$result = mysqli_query($conn, "SELECT * 
							FROM users 
							WHERE username = '" . $user . "' " .
							" AND domain = '" . $domain . "' ");
		
		if(mysqli_num_rows($result) > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}
        
        public static function likes(){
            
            
            $liId = Authentication::liId();
	
            $likesQuery = mysqli_query("SELECT likes FROM users WHERE id = '$liId'");

            $likesArray = mysqli_fetch_array($likesQuery);

            $likes = explode("/", $likesArray[0]);

            return $likes;
            
        }
	
        //GET USER AS AN OBJECT
	public static function getUser($user, $domain, $conn){
		
            $result = mysqli_query($conn, "SELECT * FROM users 
                                                WHERE username = '" . $user . "' " . 
                                                "AND domain = '" . $domain . "'");

            if(mysqli_num_rows($result) > 0){

            $result = mysqli_fetch_assoc($result);

            $u = new User;
            $u->setId($row['id']);
            $u->setUsername($row['username']);
            $u->setDomain($row['domain']);
            $u->setLevel($row['level']);
            $u->setLikes($row['likes']);
            $u->setCreated($row['created']);
            $u->setModified($row['modified']);
            $u->setStatus($row['status']);

            return $result;

            } else {
                    return FALSE;
            }
		
	}
}


?>