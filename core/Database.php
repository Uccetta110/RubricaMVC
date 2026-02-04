<?php

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $config = require __DIR__ . '/../config/config.php';
        
        try {
            if ($config['db_type'] === 'sqlite') {
                $dsn = "sqlite:{$config['db_path']}";
                $this->connection = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } else {
                $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";
                $this->connection = new PDO($dsn, $config['db_user'], $config['db_pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            }
        } catch (PDOException $e) {
            die("Connessione fallita: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    public function escape($string) {
        return $this->connection->quote($string);
    }
}
