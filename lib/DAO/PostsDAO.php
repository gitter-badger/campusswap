<?php
/**
 * User: vaskaloidis
 * Date: 10/28/14
 * Time: 12:27 AM
 */


//TODO: Implement the DAO Interface here too
class PostsDAO{

    public static $conn, $sql, $limit_sql, $total_count, $fp_count, $log, $dir;
    
    public $postSql;
    public $error = false;

    public function __construct($Connection, $Config, $log) {
        self::$conn = $Connection;
        self::$log = $log;


        $dir = Config::get('dir');
        self::$dir = $dir;
        include $dir . 'lib/Post.php';

    }

    public function updatePosts($field, $update, $id){

        $sql = "UPDATE posts SET " . $field . " = '" . $update . "' WHERE id = '" . $id . "' ";

        $result = mysqli_query(self::$Conn, $sql);

        if($result){
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function deleteItem($id){
        $sql = "DELETE FROM posts WHERE id='$id'";

        $result = mysqli_query(self::$Conn, $sql);

        if($result){
            return true;
        } else {
            return false;
        }
    }

    public function likeItem($user_id, $id) {

        $result = mysqli_query(self::$Conn, "UPDATE posts SET hits = hits +1 WHERE id = '$id'");

        $update = '/' . $id;
        $result2 = mysqli_query(self::$Conn, "UPDATE users SET likes = CONCAT(likes, '$update') WHERE id = '$user_id'");

        if($result && $result2) {
            self::$log->log($user_id, 'INFO', $user_id . ' liked item ' . $id);
            return true;
        } else {
            self::$log->log($user_id, 'ERROR', $user_id . ' error liking item ' . $id);
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
     * @param type $Conn DB connection
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
        
        $item = mysqli_real_escape_string(self::$Conn, $itemUnfiltered);
        $description = mysqli_real_escape_string(self::$Conn, $descriptionUnfiltered);

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
            $query = mysqli_query(self::$Conn, $this->postSql);
            self::$log->log(AuthenticationDAO::liFullName(), "info", "Created Post: " . mysqli_insert_id(self::$Conn), 'post create');
        } catch(mysqli_sql_exception $me) {
            self::$log->log(AuthenticationDAO::liFullName(), "error", "Mysqli Query Error inserting '" . $item, $me);
        } catch(Exception $e) {
            self::$log->log(AuthenticationDAO::liFullName(), "error", "Error Creating Post: ", $e);
        }

        if($query){
            self::$log->log(AuthenticationDAO::liFullName(), "action", "item created -'" . $item . "' - " . $description . ", " . $price . ", " . $image);
            return TRUE;
        } else {
            $this->error = mysqli_error(self::$Conn);
            self::$log->log(AuthenticationDAO::liFullName(), "error", $this->error);
            return FALSE;
        }

    }

    public function setSQL(
        $college = 'all',
        $search = false,
        $sort = false){

        self::$sql = 'SELECT * FROM posts ';

        if($college != 'all' && $college != false){
            self::$sql .= "WHERE domain = '" . $college . "' ";
        } else if(!Parser::isFalse($search)) {
            self::$sql .= "WHERE ";
        }

        if(!Parser::isFalse($search)){
            if($college!='all'){
                self::$sql .= "AND ";
            }
            self::$sql .= "item LIKE '%" . mysqli_real_escape_string(self::$Conn, $search) . "%' ";
        }

        if(!Parser::isFalse($sort)){
            self::$sql .= $this->getSortSql($sort);
        }

        return self::$sql;

    }

    public function getTotalRows($sql){
        $query = mysqli_query(self::$Conn, $sql);
        self::$total_count = mysqli_num_rows($query);
        return self::$total_count;
    }

    public function getPosts(
        $college = 'all',
        $search = false,
        $sort = false,
        $first_limit = -1,
        $second_limit = -1){

        self::$sql = $this->setSql($college, $search, $sort);

        self::$log->log(AuthenticationDAO::liFullName(), "debug", "Debug SQL: " . self::$sql);

        self::$limit_sql = self::$sql;

        if($first_limit >= 0){
            self::$limit_sql .= "LIMIT ";
            if($second_limit >= 0){
                self::$limit_sql .= $first_limit . ", " . $second_limit;
            } else {
                $second_limit = 10;
                self::$limit_sql .= $first_limit . ", " . $second_limit;
            }
        }

        $return = $this->createObject(self::$limit_sql);

        if($return) {
            return $return;
        } else {
            self::$log->log(AuthenticationDAO::liFullName(), "fatal", "There was an error loading the posts from the Posts DAO");
            return false;
        }
    }

    public function getSortSql($sort) {
        switch($sort){
            case 'none':
                return "";
            break;
            case 'dateAsc':
                return "ORDER BY created ASC ";
            break;
            case 'dateDesc':
                return "ORDER BY created DESC ";
            break;
            case 'priceLowHigh':
                return "ORDER BY price ASC ";
                break;
            case 'priceHighLow':
                return "ORDER BY price DESC ";
                break;
            case 'hitsAsc':
                return "ORDER BY hits ASC ";
                break;
            case 'hitsDesc':
                return "ORDER BY hits DESC ";
                break;
            default :
                return "ORDER BY id ASC "; //TODO: FIX TO ORER BY DESC for default query
                break;
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
        } else if(Parser::isFalse($sort)) {
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
        self::$log->log(AuthenticationDAO::liFullName(), "debug", "Attempting to create Posts Object from " . $sql);

        $result = mysqli_query(self::$Conn, $sql);

        self::$fp_count = mysqli_num_rows($result);

        if(self::$fp_count > 1){
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
        } else if(self::$fp_count == 1) {
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