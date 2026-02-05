<?php

// Wrapper MySQLi per compatibilità con PDO
class MySQLiWrapper {
    private $conn;
    
    public function __construct($host, $user, $pass, $dbname) {
        $this->conn = new mysqli($host, $user, $pass, $dbname);
        if ($this->conn->connect_error) {
            throw new Exception("Errore connessione: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }
    
    public function prepare($sql) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Errore prepare: " . $this->conn->error);
        }
        return new MySQLiStmtWrapper($stmt);
    }
    
    public function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            throw new Exception("Errore query: " . $this->conn->error);
        }
        return new MySQLiResultWrapper($result);
    }
}

class MySQLiStmtWrapper {
    private $stmt;
    private $result;
    
    public function __construct($stmt) {
        $this->stmt = $stmt;
    }
    
    public function execute($params = []) {
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $this->stmt->bind_param($types, ...$params);
        }
        if (!$this->stmt->execute()) {
            throw new Exception("Errore execute: " . $this->stmt->error);
        }
        $this->result = $this->stmt->get_result();
        return true;
    }
    
    public function fetch() {
        if (!$this->result) {
            return null;
        }
        return $this->result->fetch_assoc();
    }
    
    public function fetchAll() {
        if (!$this->result) {
            return [];
        }
        return $this->result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function rowCount() {
        return $this->stmt->affected_rows;
    }
}

class MySQLiResultWrapper {
    private $result;
    
    public function __construct($result) {
        $this->result = $result;
    }
    
    public function fetch() {
        return $this->result->fetch_assoc();
    }
    
    public function fetchAll() {
        return $this->result->fetch_all(MYSQLI_ASSOC);
    }
}

$config = [
    'db_host' => 'localhost',
    'db_user' => 'rubrica_user',
    'db_pass' => 'rubrica_pass',
    'db_name' => 'telefono',
];

try {
    $db = new MySQLiWrapper($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
} catch (Exception $e) {
    die("Errore connessione: " . $e->getMessage());
}
