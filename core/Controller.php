<?php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = 'views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: $viewFile");
        }
        
        ob_start();
        include 'views/layouts/header.php';
        include $viewFile;
        include 'views/layouts/footer.php';
        echo ob_get_clean();
    }
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
    
    protected function requireAdmin() {
        $this->requireLogin();
        
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            $this->redirect('/dashboard');
        }
    }
}

