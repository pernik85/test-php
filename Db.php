<?php
/**
 * Class Db
 */
class Db {
    private static $_db = null;

    const  USERNAME = "root";
    const  PASSWORD = "";
    const  HOST = "127.0.0.1";
    const  DB = "test-php";

    /**
     * Подключаем БД
     * @return null|PDO
     */
    public static function instance(){
        $username = self::USERNAME;
        $password = self::PASSWORD;
        $host = self::HOST;
        $db = self::DB;

        if(self::$_db === null){
            self::$_db = new PDO("mysql:dbname=$db;host=$host", $username, $password);
        }
        return self::$_db;
    }

    /**
     * Предотвращаем дублирование соединения
     */
    private function __sleep(){}
    private function __wakeup(){}
    private function __clone() {}
    private function __construct() {}


}