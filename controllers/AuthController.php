<?php
class AuthController extends Controller {
    public function loginForm() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth/login');
    }
    
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $this->view('auth/login', ['error' => 'Please enter both username and password']);
            return;
        }
        
        if (Auth::login($username, $password)) {
            $this->redirect('/dashboard');
        } else {
            $this->view('auth/login', ['error' => 'Invalid username or password']);
        }
    }
    
    public function registerForm() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth/register');
    }
    
    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $this->view('auth/register', ['error' => 'Please fill in all fields']);
            return;
        }
        
        if ($password !== $confirmPassword) {
            $this->view('auth/register', ['error' => 'Passwords do not match']);
            return;
        }
        
        if (Auth::register($username, $password)) {
            $this->view('auth/login', ['success' => 'Registration successful. Please log in.']);
        } else {
            $this->view('auth/register', ['error' => 'Username already exists']);
        }
    }
    
    public function logout() {
        Auth::logout();
        $this->redirect('/login');
    }
}

