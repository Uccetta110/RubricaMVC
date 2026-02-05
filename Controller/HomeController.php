<?php

function renderView($viewName, $data = []) {
    extract($data);
    require __DIR__ . '/../View/' . $viewName . '.php';
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Home
function homeIndex() {
    global $db;
    $mioNumero = $_SESSION['mio_numero'] ?? null;
    $unreadCount = 0;
    
    if ($mioNumero) {
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM sms WHERE destinatario = ? AND letto = 0");
        $stmt->execute([$mioNumero]);
        $unreadCount = $stmt->fetch()['count'];
    }
    
    renderView('home', [
        'mioNumero' => $mioNumero,
        'unreadCount' => $unreadCount
    ]);
}

// Rubrica
function homeRubrica() {
    global $db;
    $action = $_POST['azione'] ?? null;
    $showForm = $action !== null;
    $output = '';
    
    if (isset($_POST['execute'])) {
        $execute = $_POST['execute'];
        
        switch ($execute) {
            case 'show':
                $stmt = $db->query("SELECT id, nome, numero FROM rubrica ORDER BY id");
                $contatti = $stmt->fetchAll();
                renderView('rubrica_show', ['contatti' => $contatti]);
                return;
                
            case 'add':
                $nome = $_POST['nome'] ?? '';
                $numero = $_POST['numero'] ?? '';
                
                if (!empty($nome) && !empty($numero)) {
                    try {
                        $stmt = $db->prepare("INSERT INTO rubrica (nome, numero) VALUES (?, ?)");
                        $stmt->execute([$nome, $numero]);
                        $output = ['type' => 'success', 'message' => '✓ Contatto aggiunto con successo!'];
                    } catch (Exception $e) {
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
                    $stmt = $db->prepare("UPDATE rubrica SET nome=?, numero=? WHERE id=?");
                    $stmt->execute([$nome, $numero, $id]);
                    if ($stmt->rowCount() > 0) {
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
                    $stmt = $db->prepare("DELETE FROM rubrica WHERE id=?");
                    $stmt->execute([$id]);
                    if ($stmt->rowCount() > 0) {
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
    
    renderView('rubrica_form', [
        'showForm' => $showForm,
        'selectedAction' => $action,
        'output' => $output
    ]);
}

// Login
function homeLogin() {
    global $db;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numero = $_POST['numero'] ?? '';
        
        if (!empty($numero)) {
            $stmt = $db->prepare("SELECT id, nome, numero FROM rubrica WHERE numero = ?");
            $stmt->execute([$numero]);
            $contatto = $stmt->fetch();
            
            if ($contatto) {
                $_SESSION['mio_numero'] = $numero;
                $_SESSION['mio_nome'] = $contatto['nome'];
                redirect('/');
            } else {
                renderView('login', ['error' => 'Numero non trovato in rubrica']);
                return;
            }
        }
    }
    
    renderView('login');
}

// Logout
function homeLogout() {
    unset($_SESSION['mio_numero']);
    unset($_SESSION['mio_nome']);
    redirect('/');
}
