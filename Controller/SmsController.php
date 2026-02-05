<?php

function checkLogin() {
    if (!isset($_SESSION['mio_numero'])) {
        redirect('/?action=login');
    }
}

// Inbox
function smsInbox() {
    global $db;
    checkLogin();
    
    $mioNumero = $_SESSION['mio_numero'];
    $stmt = $db->prepare("SELECT s.*, r.nome as mittente_nome FROM sms s LEFT JOIN rubrica r ON s.mittente = r.numero WHERE s.destinatario = ? ORDER BY s.data_invio DESC");
    $stmt->execute([$mioNumero]);
    $messaggi = $stmt->fetchAll();
    
    // Segna come letti gli SMS visualizzati
    foreach ($messaggi as $msg) {
        if (!$msg['letto']) {
            $stmt = $db->prepare("UPDATE sms SET letto = 1 WHERE id = ?");
            $stmt->execute([$msg['id']]);
        }
    }
    
    renderView('sms_inbox', [
        'messaggi' => $messaggi,
        'mioNumero' => $mioNumero
    ]);
}

// Compose
function smsCompose() {
    global $db;
    checkLogin();
    
    $mioNumero = $_SESSION['mio_numero'];
    $destinatario = $_GET['destinatario'] ?? '';
    
    $stmt = $db->query("SELECT id, nome, numero FROM rubrica ORDER BY id");
    $contatti = $stmt->fetchAll();
    
    renderView('sms_compose', [
        'contatti' => $contatti,
        'mioNumero' => $mioNumero,
        'destinatario' => $destinatario
    ]);
}

// Send
function smsSend() {
    global $db;
    checkLogin();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mioNumero = $_SESSION['mio_numero'];
        $destinatario = $_POST['destinatario'] ?? '';
        $messaggio = $_POST['messaggio'] ?? '';
        
        if (!empty($destinatario) && !empty($messaggio)) {
            try {
                $stmt = $db->prepare("INSERT INTO sms (mittente, destinatario, messaggio, data_invio, letto) VALUES (?, ?, ?, datetime('now'), 0)");
                $stmt->execute([$mioNumero, $destinatario, $messaggio]);
                
                renderView('sms_sent', [
                    'success' => true,
                    'destinatario' => $destinatario
                ]);
            } catch (Exception $e) {
                renderView('sms_sent', [
                    'success' => false
                ]);
            }
        } else {
            redirect('/?action=sms_compose');
        }    }
}