<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Sms.php';
require_once __DIR__ . '/../models/Rubrica.php';

class SmsController extends Controller {
    private $smsModel;
    private $rubricaModel;
    
    public function __construct() {
        $this->smsModel = new Sms();
        $this->rubricaModel = new Rubrica();
    }
    
    private function checkLogin() {
        if (!isset($_SESSION['mio_numero'])) {
            $this->redirect('/public/index.php?action=login');
        }
    }
    
    public function inbox() {
        $this->checkLogin();
        
        $mioNumero = $_SESSION['mio_numero'];
        $messaggi = $this->smsModel->getReceived($mioNumero);
        
        // Segna come letti gli SMS visualizzati
        foreach ($messaggi as $msg) {
            if (!$msg['letto']) {
                $this->smsModel->markAsRead($msg['id']);
            }
        }
        
        $this->view('sms/inbox', [
            'messaggi' => $messaggi,
            'mioNumero' => $mioNumero
        ]);
    }
    
    public function compose() {
        $this->checkLogin();
        
        $mioNumero = $_SESSION['mio_numero'];
        $destinatario = $_GET['destinatario'] ?? '';
        $contatti = $this->rubricaModel->getAll();
        
        // Verifica se il destinatario sta scrivendo a me
        $isTyping = false;
        if ($destinatario) {
            $isTyping = $this->smsModel->isTyping($mioNumero, $destinatario);
        }
        
        $this->view('sms/compose', [
            'contatti' => $contatti,
            'mioNumero' => $mioNumero,
            'destinatario' => $destinatario,
            'isTyping' => $isTyping
        ]);
    }
    
    public function send() {
        $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mioNumero = $_SESSION['mio_numero'];
            $destinatario = $_POST['destinatario'] ?? '';
            $messaggio = $_POST['messaggio'] ?? '';
            
            if (!empty($destinatario) && !empty($messaggio)) {
                // Rimuove lo stato "sta scrivendo"
                $this->smsModel->setTypingStatus($mioNumero, $destinatario, false);
                
                if ($this->smsModel->send($mioNumero, $destinatario, $messaggio)) {
                    $this->view('sms/sent', [
                        'success' => true,
                        'destinatario' => $destinatario
                    ]);
                } else {
                    $this->view('sms/sent', [
                        'success' => false
                    ]);
                }
            } else {
                $this->redirect('/public/index.php?action=sms_compose');
            }
        }
    }
    
    public function setTyping() {
        $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mioNumero = $_SESSION['mio_numero'];
            $destinatario = $_POST['destinatario'] ?? '';
            $isTyping = ($_POST['typing'] ?? '0') === '1';
            
            if (!empty($destinatario)) {
                $this->smsModel->setTypingStatus($mioNumero, $destinatario, $isTyping);
                $this->json(['success' => true]);
            }
        }
        
        $this->json(['success' => false]);
    }
    
    public function checkTyping() {
        $this->checkLogin();
        
        $mioNumero = $_SESSION['mio_numero'];
        $destinatario = $_GET['destinatario'] ?? '';
        
        if (!empty($destinatario)) {
            $isTyping = $this->smsModel->isTyping($mioNumero, $destinatario);
            $this->json(['typing' => $isTyping]);
        }
        
        $this->json(['typing' => false]);
    }
}
