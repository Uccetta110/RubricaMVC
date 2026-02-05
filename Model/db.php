<?php

$config = [
    'db_host' => 'localhost',
    'db_socket' => '/run/mysqld/mysqld.sock',
    'db_user' => 'root',
    'db_pass' => '',
    'db_name' => 'telefono',
];

try {
    $dsn = "mysql:unix_socket={$config['db_socket']};dbname={$config['db_name']};charset=utf8mb4";
    $db = new PDO($dsn, $config['db_user'], $config['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Errore connessione: " . $e->getMessage());
}
