<?php

class posts {
    
    public static $conn, $sql, $query, $posts, $rowCount;
    
    private $id; 
    private $item; 
    private $description; 
    private $username; 
    private $domain; 
    private $price; 
    private $hits; 
    private $views; 
    private $created; 
    private $createdSince; 
    private $modified; 
    private $img;


    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
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

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getHits() {
        return $this->hits;
    }

    public function setHits($hits) {
        $this->hits = $hits;
    }

    public function getViews() {
        return $this->views;
    }

    public function setViews($views) {
        $this->views = $views;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getCreatedSince() {
        return $this->createdSince;
    }

    public function setCreatedSince($createdSince) {
        $this->createdSince = $createdSince;
    }

    public function getModified() {
        return $this->modified;
    }

    public function setModified($modified) {
        $this->modified = $modified;
    }

    public function getImg() {
        return $this->img;
    }

    public function setImg($img) {
        $this->img = $img;
    }

    
}



/*


switch(sort){
    
    case 'priceLowHigh':
        
        break;
    case 'priceHighLow':
        
        break;
    case 'hitsAsc':
        
        break;
    case 'hitsDesc':
       
        break;
    default :
        
        break;
}


*/

    /*
	$result = array();
    if(mysqli_num_rows(self::$query) > 0){
        foreach(mysqli_fetch_assoc(self::$query) as $row){
            $posts = new posts();
            $posts->setId($row['id']);
            $posts->setItem($row['item']); 
            $posts->setDescription($row['description']); 
            $posts->setDomain($row['domain']); 
            $posts->setPrice($row['price']); 
            $posts->setHits($row['hits']); 
            $posts->setViews($row['views']); 
            $posts->setCreated($row['created']); 
            $posts->setCreatedSince($row['createdSince']); 
            $posts->setModified($row['modified']); 
            $posts->setImg($row['img']);
            $results[] = $posts;
        }   
    }
*/

?>