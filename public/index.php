<?php
session_start();

// Connessione database
require_once __DIR__ . '/../Model/db.php';

// Controllers
require_once __DIR__ . '/../Controller/HomeController.php';
require_once __DIR__ . '/../Controller/SmsController.php';

// Routing
$action = $_GET['action'] ?? $_POST['action'] ?? 'home';

switch ($action) {
    case 'home':
        homeIndex();
        break;
    
    case 'login':
        homeLogin();
        break;
    
    case 'logout':
        homeLogout();
        break;
    
    case 'rubrica':
        homeRubrica();
        break;
    
    case 'sms_inbox':
        smsInbox();
        break;
    
    case 'sms_compose':
        smsCompose();
        break;
    
    case 'sms_send':
        smsSend();
        break;
    
    default:
        homeIndex();
        break;
}