<?php
session_start();

// Carica il framework MVC
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/SmsController.php';

// Inizializza il database
Database::getInstance();

// Gestione semplificata tramite GET/POST action
$action = $_GET['action'] ?? $_POST['action'] ?? 'home';

// Routing semplificato
$homeController = new HomeController();
$smsController = new SmsController();

switch ($action) {
    case 'home':
        $homeController->index();
        break;
    
    case 'login':
        $homeController->login();
        break;
    
    case 'logout':
        $homeController->logout();
        break;
    
    case 'rubrica':
        $homeController->rubrica();
        break;
    
    case 'sms_inbox':
        $smsController->inbox();
        break;
    
    case 'sms_compose':
        $smsController->compose();
        break;
    
    case 'sms_send':
        $smsController->send();
        break;
    
    case 'sms_typing':
        $smsController->setTyping();
        break;
    
    case 'sms_check_typing':
        $smsController->checkTyping();
        break;
    
    default:
        $homeController->index();
        break;
}