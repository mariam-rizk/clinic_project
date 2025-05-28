<?php

namespace App\core;

use PDO;
use PDOException;
use App\core\Logger;


class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct(array $config) {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password']);

        } catch (PDOException $e) {

            Logger::error("Database connection failed: " . $e->getMessage());
            die("Sorry, we're experiencing technical difficulties. Please try again later.");
}

    }

    public static function getInstance(array $config): self {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}



 
