<?php

class Sms {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function send($mittente, $destinatario, $messaggio) {
        $stmt = $this->db->prepare("INSERT INTO sms (mittente, destinatario, messaggio, data_invio, letto) VALUES (?, ?, ?, datetime('now'), 0)");
        return $stmt->execute([$mittente, $destinatario, $messaggio]);
    }
    
    public function getReceived($numero) {
        $stmt = $this->db->prepare("SELECT s.*, r.nome as mittente_nome FROM sms s LEFT JOIN rubrica r ON s.mittente = r.numero WHERE s.destinatario = ? ORDER BY s.data_invio DESC");
        $stmt->execute([$numero]);
        return $stmt->fetchAll();
    }
    
    public function getSent($numero) {
        $stmt = $this->db->prepare("SELECT s.*, r.nome as destinatario_nome FROM sms s LEFT JOIN rubrica r ON s.destinatario = r.numero WHERE s.mittente = ? ORDER BY s.data_invio DESC");
        $stmt->execute([$numero]);
        return $stmt->fetchAll();
    }
    
    public function markAsRead($id) {
        $stmt = $this->db->prepare("UPDATE sms SET letto = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function countUnread($numero) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM sms WHERE destinatario = ? AND letto = 0");
        $stmt->execute([$numero]);
        $row = $stmt->fetch();
        return $row['count'];
    }
    
    public function setTypingStatus($numero, $destinatario, $isTyping) {
        // Rimuove vecchi stati di scrittura (più vecchi di 10 secondi)
        $this->db->exec("DELETE FROM typing_status WHERE (julianday('now') - julianday(ultimo_aggiornamento)) * 86400 > 10");
        
        if ($isTyping) {
            $stmt = $this->db->prepare("INSERT OR REPLACE INTO typing_status (numero, destinatario, ultimo_aggiornamento) VALUES (?, ?, datetime('now'))");
            $stmt->execute([$numero, $destinatario]);
        } else {
            $stmt = $this->db->prepare("DELETE FROM typing_status WHERE numero = ? AND destinatario = ?");
            $stmt->execute([$numero, $destinatario]);
        }
        
        return true;
    }
    
    public function isTyping($numero, $destinatario) {
        // Controlla se il destinatario sta scrivendo a me
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM typing_status WHERE numero = ? AND destinatario = ? AND (julianday('now') - julianday(ultimo_aggiornamento)) * 86400 <= 10");
        $stmt->execute([$destinatario, $numero]);
        $row = $stmt->fetch();
        return $row['count'] > 0;
    }
}
