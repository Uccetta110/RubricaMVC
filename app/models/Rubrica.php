<?php

class Rubrica {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT id, nome, numero FROM rubrica ORDER BY id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nome, numero FROM rubrica WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByNumero($numero) {
        $stmt = $this->db->prepare("SELECT id, nome, numero FROM rubrica WHERE numero = ?");
        $stmt->execute([$numero]);
        return $stmt->fetch();
    }
    
    public function add($nome, $numero) {
        $stmt = $this->db->prepare("INSERT INTO rubrica (nome, numero) VALUES (?, ?)");
        return $stmt->execute([$nome, $numero]);
    }
    
    public function update($id, $nome, $numero) {
        $stmt = $this->db->prepare("UPDATE rubrica SET nome=?, numero=? WHERE id=?");
        $stmt->execute([$nome, $numero, $id]);
        return $stmt->rowCount() > 0;
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM rubrica WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
