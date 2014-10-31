<?php

class Domains {

private static $conn;
public static $domains;

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

public static function getDomains(){
    
    //$query = "SELECT * FROM domains";
    
    $conn = self::$conn;

    $results = mysqli_query($conn, "SELECT * FROM domains");
    
    return mysqli_fetch_assoc($results);

	
}

public function fetchColleges(){
    
    $sql = "SELECT * FROM domains";
    
    $conn = self::$conn;
    
    $query = mysqli_query($conn, $sql);

    self::$domains = array();
    
    while($row = mysqli_fetch_assoc($query)){
        self::$domains[] = $row;
    }
    
    mysqli_free_result($query);
    
}


public function getColleges(){
    
    return self::$domains;;
    
}

public static function getCollegeName($req){

    if($req == 'all'){
        return 'All';
    } else {
        $conn = self::$conn;

        $nameQuery = mysqli_query($conn, "SELECT name FROM domains WHERE domain = '$req'");

        $nameQueryArray = mysqli_fetch_assoc($nameQuery);

        $toReturn = $nameQueryArray['name'];

        return $toReturn;
    }
}

public static function domainExists($domain, $conn){
	$query = "SELECT * from domains WHERE domain = '" . $domain . "' ";
	
	$results = mysqli_query($conn, $query);
	
	if(mysqli_num_rows($results) > 0){
		return TRUE;
	} else {
		return FALSE;
	}
	
}

public function __construct($conn){
    
    self::$conn = $conn;
}
	
	
	
}


?>