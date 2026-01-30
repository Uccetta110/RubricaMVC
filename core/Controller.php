<?php

class Controller {
    protected function view($viewName, $data = []) {
        extract($data);
        require __DIR__ . '/../app/views/' . $viewName . '.php';
    }
    
    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
