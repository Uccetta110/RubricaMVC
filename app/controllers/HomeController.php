<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Rubrica.php';
require_once __DIR__ . '/../models/Sms.php';

class HomeController extends Controller {
    private $rubricaModel;
    private $smsModel;
    
    public function __construct() {
        $this->rubricaModel = new Rubrica();
        $this->smsModel = new Sms();
    }
    
    public function index() {
        $mioNumero = $_SESSION['mio_numero'] ?? null;
        $unreadCount = 0;
        
        if ($mioNumero) {
            $unreadCount = $this->smsModel->countUnread($mioNumero);
        }
        
        $this->view('home', [
            'mioNumero' => $mioNumero,
            'unreadCount' => $unreadCount
        ]);
    }
    
    public function rubrica() {
        $action = $_POST['azione'] ?? null;
        $showForm = $action !== null;
        $output = '';
        
        if (isset($_POST['execute'])) {
            $execute = $_POST['execute'];
            
            switch ($execute) {
                case 'show':
                    $contatti = $this->rubricaModel->getAll();
                    $this->view('rubrica/show', ['contatti' => $contatti]);
                    return;
                    
                case 'add':
                    $nome = $_POST['nome'] ?? '';
                    $numero = $_POST['numero'] ?? '';
                    
                    if (!empty($nome) && !empty($numero)) {
                        if ($this->rubricaModel->add($nome, $numero)) {
                            $output = ['type' => 'success', 'message' => '✓ Contatto aggiunto con successo!'];
                        } else {
                            $output = ['type' => 'error', 'message' => '✗ Errore nell\'aggiunta'];
                        }
                    } else {
                        $output = ['type' => 'error', 'message' => '⚠ Compila tutti i campi'];
                    }
                    break;
                    
                case 'mod':
                    $id = $_POST['id'] ?? '';
                    $nome = $_POST['nome'] ?? '';
                    $numero = $_POST['numero'] ?? '';
                    
                    if (!empty($id) && !empty($nome) && !empty($numero)) {
                        if ($this->rubricaModel->update($id, $nome, $numero)) {
                            $output = ['type' => 'success', 'message' => '✓ Contatto modificato con successo!'];
                        } else {
                            $output = ['type' => 'warning', 'message' => '⚠ Nessuna modifica effettuata o ID non trovato'];
                        }
                    } else {
                        $output = ['type' => 'error', 'message' => '⚠ Compila tutti i campi'];
                    }
                    break;
                    
                case 'del':
                    $id = $_POST['id'] ?? '';
                    
                    if (!empty($id)) {
                        if ($this->rubricaModel->delete($id)) {
                            $output = ['type' => 'success', 'message' => '✓ Contatto eliminato con successo!'];
                        } else {
                            $output = ['type' => 'warning', 'message' => '⚠ ID non trovato'];
                        }
                    } else {
                        $output = ['type' => 'error', 'message' => '⚠ Inserisci un ID valido'];
                    }
                    break;
            }
        }
        
        $this->view('rubrica/form', [
            'showForm' => $showForm,
            'selectedAction' => $action,
            'output' => $output
        ]);
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = $_POST['numero'] ?? '';
            
            if (!empty($numero)) {
                // Verifica se il numero esiste in rubrica
                $contatto = $this->rubricaModel->getByNumero($numero);
                
                if ($contatto) {
                    $_SESSION['mio_numero'] = $numero;
                    $_SESSION['mio_nome'] = $contatto['nome'];
                    $this->redirect('/public/index.php');
                } else {
                    $this->view('login', ['error' => 'Numero non trovato in rubrica']);
                    return;
                }
            }
        }
        
        $this->view('login');
    }
    
    public function logout() {
        unset($_SESSION['mio_numero']);
        unset($_SESSION['mio_nome']);
        $this->redirect('/public/index.php');
    }
}
