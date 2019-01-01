<?php

namespace app\database;

use \Doctrine\DBAL\Configuration;

/**
 * Class connection
 * @package app\database
 *
 * Doctrine implementation using dotenv for connection variables.
 */
class connection
{
    /** @var \Doctrine\DBAL\Connection */
    static private $conn;

    /**
     * Primary entry point for accessing the database connection.
     * Cached within each request.
     * @return \Doctrine\DBAL\Connection
     */
    static public function conn() {
        if(self::$conn !== null) {
            return self::$conn;
        }

        $config = new Configuration();
        $connectionParams = array(
            'dbname' =>     getenv('DB_DBNAME'),
            'user' =>       getenv('DB_USER'),
            'password' =>   getenv('DB_PASSWORD'),
            'host' =>       getenv('DB_HOST'),
            'port' =>       getenv('DB_PORT'),
            'driver' =>     getenv('DB_DRIVER'),
        );

        self::$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        return self::$conn;
    }
}