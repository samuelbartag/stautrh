<?php

namespace App\Database;

use App\Config;

class Connector {

    /**
     * database instance
     * @var type
     */
    private $db;

    public function __construct()
    {
        try {
            $this->db = new \PDO("sqlite:" . Config::DB_FILE);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function getConn()
    {
        return $this->db;
    }
}
