<?php

class Sms {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function send($mittente, $destinatario, $messaggio) {
        $stmt = $this->db->prepare("INSERT INTO sms (mittente, destinatario, messaggio, data_invio, letto) VALUES (?, ?, ?, NOW(), 0)");
        $stmt->bind_param("sss", $mittente, $destinatario, $messaggio);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public function getReceived($numero) {
        $stmt = $this->db->prepare("SELECT s.*, r.nome as mittente_nome FROM sms s LEFT JOIN rubrica r ON s.mittente = r.numero WHERE s.destinatario = ? ORDER BY s.data_invio DESC");
        $stmt->bind_param("s", $numero);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $messaggi = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messaggi[] = $row;
            }
        }
        $stmt->close();
        return $messaggi;
    }
    
    public function getSent($numero) {
        $stmt = $this->db->prepare("SELECT s.*, r.nome as destinatario_nome FROM sms s LEFT JOIN rubrica r ON s.destinatario = r.numero WHERE s.mittente = ? ORDER BY s.data_invio DESC");
        $stmt->bind_param("s", $numero);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $messaggi = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messaggi[] = $row;
            }
        }
        $stmt->close();
        return $messaggi;
    }
    
    public function markAsRead($id) {
        $stmt = $this->db->prepare("UPDATE sms SET letto = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public function countUnread($numero) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM sms WHERE destinatario = ? AND letto = 0");
        $stmt->bind_param("s", $numero);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }
    
    public function setTypingStatus($numero, $destinatario, $isTyping) {
        // Rimuove vecchi stati di scrittura (più vecchi di 10 secondi)
        $this->db->query("DELETE FROM typing_status WHERE TIMESTAMPDIFF(SECOND, ultimo_aggiornamento, NOW()) > 10");
        
        if ($isTyping) {
            $stmt = $this->db->prepare("INSERT INTO typing_status (numero, destinatario, ultimo_aggiornamento) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE ultimo_aggiornamento = NOW()");
            $stmt->bind_param("ss", $numero, $destinatario);
        } else {
            $stmt = $this->db->prepare("DELETE FROM typing_status WHERE numero = ? AND destinatario = ?");
            $stmt->bind_param("ss", $numero, $destinatario);
        }
        
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public function isTyping($numero, $destinatario) {
        // Controlla se il destinatario sta scrivendo a me
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM typing_status WHERE numero = ? AND destinatario = ? AND TIMESTAMPDIFF(SECOND, ultimo_aggiornamento, NOW()) <= 10");
        $stmt->bind_param("ss", $destinatario, $numero);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
}
