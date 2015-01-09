<?php
/**
 * Created by PhpStorm.
 * User: vaskaloidis
 * Date: 10/28/14
 * Time: 12:27 AM
 */

class PostsDAO {

    public static $conn, $sql, $limit_sql, $total_count, $fp_count, $log, $dir;

    public function __construct($connection, $config, $log) {
        self::$conn = $connection;
        self::$log = $log;


        $dir = Config::get('dir');
        self::$dir = $dir;
        include $dir . 'lib/Post.php';

    }

    public function updatePosts($field, $update, $id){

        $sql = "UPDATE posts SET " . $field . " = '" . $update . "' WHERE id = '" . $id . "' ";

        $result = mysqli_query(self::$conn, $sql);

        if($result){
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function deleteItem($id){
        $sql = "DELETE FROM posts WHERE id='$id'";

        $result = mysqli_query(self::$conn, $sql);

        if($result){
            return true;
        } else {
            return false;
        }
    }

    public function likeItem($user_id, $id) {

        $result = mysqli_query(self::$conn, "UPDATE posts SET hits = hits +1 WHERE id = '$id'");

        $update = '/' . $id;
        $result2 = mysqli_query(self::$conn, "UPDATE users SET likes = CONCAT(likes, '$update') WHERE id = '$user_id'");

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
        $item,
        $description,
        $username,
        $domain,
        $price,
        $image){

        if(!$image){
            $sql = "
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
            $sql = "
                INSERT INTO posts
                    (item,
                    description,
                    username,
                    domain,
                    price,
                    hits,
                    views,
                    created,
                    createdSince,
                    modified,
                    img)
                VALUES
                    ('$item',
                    '$description',
                    '$username',
                    '$domain',
                    '$price',
                    '0', '0', NOW(), NOW(), NOW(),
                    '$image')";
        }

        self::$log->log(AuthenticationDAO::liFullName(), "info", "created post: " . mysqli_insert_id(self::$conn), 'post create');

        //TODO: Sanitize
        $query = mysqli_query(self::$conn, $sql);

        if($query){
            return TRUE;
        } else {
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
            self::$sql .= "item LIKE '%" . mysqli_real_escape_string(self::$conn, $search) . "%' ";
        }

        if(!Parser::isFalse($sort)){
            self::$sql .= $this->getSortSql($sort);
        }

        return self::$sql;

    }

    public function getTotalRows($sql){
        $query = mysqli_query(self::$conn, $sql);
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

        $result = mysqli_query(self::$conn, $sql);

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