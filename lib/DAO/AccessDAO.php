<?php
/**
 * Created by PhpStorm.
 * User: vaskaloidis
 * Date: 11/9/14
 * Time: 9:37 PM
 */

class AccessDAO {

    public static $conn, $config, $count, $log;

    public function __construct($connection, $config, $log) {
        self::$conn = $connection;
        self::$config = $config;
        self::$log = $log;
        $dir = Config::get('dir');
        include $dir . 'lib/Objects/Access.php';
    }

    public function getAccessToday($ip, $username) {

        $sql = "SELECT * FROM access WHERE (ip = '" . $ip . "' AND (datetime > DATE_SUB(now(), INTERVAL 1 DAY)))";

        $return = $this->createObject($sql);

        if($return) {
            return $return;
        } else {
            $insert_query = "INSERT INTO access (ip, usernames, status, visits, failed_logins, datetime) VALUES ('$ip', '$username', 'ok', 1, 0, NOW())";

            if(!mysqli_query(self::$conn, $insert_query)) {
                self::$log->log($username, 'FATAL', 'Error inserting access record into access table');
                self::$log->log($username, 'TRACE', 'GET SQL: ' . $sql);
                self::$log->log($username, 'TRACE', 'INSERT SQL: ' . $insert_query);
            }

            return $this->createObject($sql);
        }
    }

    private function createObject($sql){

        $result = mysqli_query(self::$conn, $sql);

        self::$count = mysqli_num_rows($result);

        if(self::$count == 1){
            while($row = mysqli_fetch_array($result)){
                $Access = new Access();
                $Access->setId($row['id']);
                $Access->setIp($row['ip']);
                $Access->setUsernames($row['usernames']);
                $Access->setStatus($row['status']);
                $Access->setVisits($row['visits']);
                $Access->setFailedLogins($row['failed_logins']);
                $Access->setDatetime($row['datetime']);
            }
            return $Access;
        } else {
            return false;
        }
    }
}