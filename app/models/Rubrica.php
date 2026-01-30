<?php

class Rubrica {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT id, nome, numero FROM rubrica ORDER BY id";
        $result = $this->db->query($sql);
        
        $contatti = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contatti[] = $row;
            }
        }
        return $contatti;
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nome, numero FROM rubrica WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getByNumero($numero) {
        $stmt = $this->db->prepare("SELECT id, nome, numero FROM rubrica WHERE numero = ?");
        $stmt->bind_param("s", $numero);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function add($nome, $numero) {
        $stmt = $this->db->prepare("INSERT INTO rubrica (nome, numero) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $numero);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public function update($id, $nome, $numero) {
        $stmt = $this->db->prepare("UPDATE rubrica SET nome=?, numero=? WHERE id=?");
        $stmt->bind_param("ssi", $nome, $numero, $id);
        $success = $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM rubrica WHERE id=?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }
}
