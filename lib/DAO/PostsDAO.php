<?php
/**
 * User: vaskaloidis
 * Date: 10/28/14
 * Time: 12:27 AM
 */

namespace Campusswap\DAO;
use Campusswap\Object\Post;

//TODO: Implement the DAO Interface here too
class PostsDAO {

    public $conn, $sql, $limit_sql, $total_count, $dir;
    
    public $postSql, $fp_count, $Log;
    public $error = false;

    /**
     * The Data Access Object for the Posts Object
     * @param MySqli $connection
     * @param Config $Config
     * @param LogUtil $log
     */
    public function __construct($connection, $Config, $log) {
        $this->conn = $connection;
        $this->Log = $log;


        $dir = $Config->get('dir');
        $this->dir = $dir;
        include $dir . 'lib/Post.php';

    }

    public function updatePosts($field, $update, $id){

        $sql = "UPDATE posts SET " . $field . " = '" . $update . "' WHERE id = '" . $id . "' ";

        $result = mysqli_query($this->conn, $sql);

        if($result){
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function deleteItem($id){
        $sql = "DELETE FROM posts WHERE id='$id'";

        $result = mysqli_query($this->conn, $sql);

        if($result){
            return true;
        } else {
            return false;
        }
    }

    public function likeItem($user_id, $id) {

        $result = mysqli_query($this->conn, "UPDATE posts SET hits = hits +1 WHERE id = '$id'");

        $update = '/' . $id;
        $result2 = mysqli_query($this->conn, "UPDATE users SET likes = CONCAT(likes, '$update') WHERE id = '$user_id'");

        if($result && $result2) {
            $this->Log->log($user_id, 'INFO', $user_id . ' liked item ' . $id);
            return true;
        } else {
            $this->Log->log($user_id, 'ERROR', $user_id . ' error liking item ' . $id);
            return false;
        }
    }

    /**
     *
     * Create Post creates a post in the database from the item name, description, the username and domain, price and
     * name of the image.
     *
     * @param type $item the name of the item
     * @param type $description the item's description
     * @param type $username the usename of the author
     * @param type $domain the domain of the author
     * @param type $price the price of the item
     * @param type $image the item's image, or FALSE for no image
     * @param type $conn DB connection
     * @return boolean return TRUE if created succesfully or false if there
     *  was a problem creating it
     */
    public function createPost(
        $itemUnfiltered,
        $descriptionUnfiltered,
        $username,
        $domain,
        $price,
        $image){
        
        $fullName = $username . '@' . $domain;
        
        $item = mysqli_real_escape_string($this->conn, $itemUnfiltered);
        $description = mysqli_real_escape_string($this->conn, $descriptionUnfiltered);

        if(!$image){
            $this->postSql = "
                INSERT INTO posts
                    (item,
                    description,
                    username,
                    domain,
                    price,
                    hits,
                    views,
                    created,
                    modified,
                    img)
                VALUES
                    ('$item',
                    '$description',
                    '$username',
                    '$domain',
                    '$price',
                    '0',
                    '0',
                    NOW(),
                    NOW(),
                   'FALSE')";

        } else {
            $this->postSql = "
                INSERT INTO posts
                    (item,
                    description,
                    username,
                    domain,
                    price,
                    hits,
                    views,
                    created,
                    modified,
                    img)
                VALUES
                    ('$item',
                    '$description',
                    '$username',
                    '$domain',
                    '$price',
                    '0', '0', NOW(), NOW(),
                    '$image')";
        }

        //TODO: Sanitize data before insert query
        try {
            $query = mysqli_query($this->conn, $this->postSql);
            $this->Log->log($fullName, "info", "Created Post: " . mysqli_insert_id($this->conn), 'post create');
        } catch(mysqli_sql_exception $me) {
            $this->Log->log($fullName, "error", "Mysqli Query Error inserting '" . $item, $me);
        } catch(Exception $e) {
            $this->Log->log($fullName, "error", "Error Creating Post: ", $e);
        }

        if($query){
            $this->Log->log($fullName, "action", "item created -'" . $item . "' - " . $description . ", " . $price . ", " . $image);
            return TRUE;
        } else {
            $this->error = mysqli_error($this->conn);
            $this->Log->log($fullName, "error", $this->error);
            return FALSE;
        }

    }

    private function setSQL(
        $college = 'all',
        $search = false,
        $sort = false){

        $this->sql = 'SELECT * FROM posts ';

        if($college != 'all' && $college != false){
            $this->sql .= "WHERE domain = '" . $college . "' ";
        } else if(!$search) {
            $this->sql .= "WHERE ";
        }

        if(!$search){
            if($college!='all'){
                $this->sql .= "AND ";
            }
            $this->sql .= "item LIKE '%" . mysqli_real_escape_string($this->conn, $search) . "%' ";
        }

        if(!$sort){
            $this->sql .= $this->getSortSql($sort);
        }

        return $this->sql;

    }

    public function getTotalRows($sql){
        $query = mysqli_query($this->conn, $sql);
        $this->total_count = mysqli_num_rows($query);
        return $this->total_count;
    }

    public function getPosts(
        $college = 'all',
        $search = false,
        $sort = false,
        $first_limit = -1,
        $second_limit = -1){

        $this->sql = $this->setSql($college, $search, $sort);

        $this->Log->log('USER', "debug", "Debug SQL: " . $this->sql);

        $this->limit_sql = $this->sql;

        if($first_limit >= 0){
            $this->limit_sql .= "LIMIT ";
            if($second_limit >= 0){
                $this->limit_sql .= $first_limit . ", " . $second_limit;
            } else {
                $second_limit = 10;
                $this->limit_sql .= $first_limit . ", " . $second_limit;
            }
        }

        $return = $this->createObject($this->limit_sql);

        if($return) {
            return $return;
        } else {
            $this->Log->log('USER', "fatal", "There was an error loading the posts from the Posts DAO");
            return false;
        }
    }

    public function getSortSql($sort) {
        switch($sort){
            case 'none':
                return "";
            case 'dateAsc':
                return "ORDER BY created ASC ";
            case 'dateDesc':
                return "ORDER BY created DESC ";
            case 'priceLowHigh':
                return "ORDER BY price ASC ";
            case 'priceHighLow':
                return "ORDER BY price DESC ";
            case 'hitsAsc':
                return "ORDER BY hits ASC ";
            case 'hitsDesc':
                return "ORDER BY hits DESC ";
            default :
                return "ORDER BY id ASC "; //TODO: FIX TO ORER BY DESC for default query
        }
    }

    public function getSortText($sort) {
        $navSort = '';
        if($sort == 'none'){
            $navSort = '';
        } else if($sort == 'priceHighLow'){
            $navSort = 'Price Sorted High to Low';
        } else if($sort=='priceLowHigh'){
            $navSort = 'Price Sorted Low to High';
        } else if($sort=='hitsAsc'){
            $navSort = 'Likes Sorted Low to High';
        } else if($sort=='hitsDesc'){
            $navSort = 'Likes Sorted Low to High';
        } else if($sort=='dateDesc'){
            $navSort = 'Date Descending';
        } else if($sort=='dateAsc') {
            $navSort = 'Date Ascending';
        } else if($sort) {
            $navSort = '';
        }
        return $navSort;
    }

    public function getPostsUser($username, $domain) {
        $query = "SELECT * FROM posts WHERE username = '" . $username . "' AND domain = '" . $domain . "' ";

        $results = $this->createObject($query);

        return $results;
    }

    public function createObject($sql){
        $this->Log->log('USER'. "debug", "Attempting to create Posts Object from " . $sql);

        $result = mysqli_query($this->conn, $sql);

        $this->fp_count = mysqli_num_rows($result);

        if($this->fp_count > 1){
            while($row = mysqli_fetch_array($result)){
                $Post = new Post();
                $Post->setId($row['id']);
                $Post->setUsername($row['username']);
                $Post->setDomain($row['domain']);
                $Post->setItem($row['item']);
                $Post->setDescription($row['description']);
                $Post->setPrice($row['price']);
                $Post->setHits($row['hits']);
                $Post->setViews($row['views']);
                $Post->setImg($row['img']);
                $Post->setModified($row['modified']);
                $Post->setCreated($row['created']);
                $results[] = $Post;
            }
            return $results;
        } else if($this->fp_count == 1) {
            while($row = mysqli_fetch_array($result)){
                $Post = new Post();
                $Post->setId($row['id']);
                $Post->setUsername($row['username']);
                $Post->setDomain($row['domain']);
                $Post->setItem($row['item']);
                $Post->setDescription($row['description']);
                $Post->setPrice($row['price']);
                $Post->setHits($row['hits']);
                $Post->setViews($row['views']);
                $Post->setImg($row['img']);
                $Post->setModified($row['modified']);
                $Post->setCreated($row['created']);
                $Posts[0] = $Post;
                return $Posts;
            }
        } else {
            return false;
        }
    }

    public function getPost($item){

        $sql = "SELECT * FROM posts WHERE id = '$item'";

        return $this->createObject($sql);

        //mysqli_free_result($query); //TODO: Implement this everywhere, I think?

    }

}

?>