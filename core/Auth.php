<?php
class Auth {
    public static function login($username, $password) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Check if user is admin (you might want to add an is_admin column to users table)
            $_SESSION['is_admin'] = isset($user['is_admin']) ? (bool)$user['is_admin'] : false;
            
            return true;
        }
        
        return false;
    }
    
    public static function register($username, $password) {
        $db = Database::getInstance();
        
        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return false; // Username already exists
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        
        return $stmt->execute();
    }
    
    public static function logout() {
        session_unset();
        session_destroy();
    }
    
    public static function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

