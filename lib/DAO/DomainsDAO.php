<?php
/**

 * User: vaskaloidis
 * Date: 11/5/14
 * Time: 1:48 AM
 */

namespace Campusswap\DAO;


class DomainsDAO {

    public static $conn, $Config, $count;

    /**
     * The Data Access Object for Domain Object.
     * @param type $connection
     * @param type $Config
     * @param type $log
     */
    public function __construct($connection, $Config, $log) {
        self::$conn = $connection;
        self::$Config = $Config;
        // $dir = $Config->get('dir');
        // include $dir . 'lib/Domain.php';

    }

    private function createObject($sql){

        $result = mysqli_query(self::$conn, $sql);

        self::$count = mysqli_num_rows($result);

        if(self::$count > 1){
            while($row = mysqli_fetch_array($result)){
                $Domain = new \Campusswap\Object\Domain();
                $Domain->setId($row['id']);
                $Domain->setName($row['name']);
                $Domain->setDomain($row['domain']);
                $results[] = $Domain;
            }
            return $results;
        } else if(self::$count == 1) {
            while($row = mysqli_fetch_array($result)){
                $Domain = new \Campusswap\Object\Domain();
                $Domain->setId($row['id']);
                $Domain->setName($row['name']);
                $Domain->setDomain($row['domain']);
                return $Domain;
            }
        } else {
            return false;
        }
    }

    public function getAllDomains(){

        $sql = "SELECT * FROM domains";

        return $this->createObject($sql);


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

} 