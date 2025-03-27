<?php
class Permissions {
    // Unix-style permissions: rwx (read, write, execute)
    // For files, execute <=> download
    const READ = 4;
    const WRITE = 2;
    const EXECUTE = 1;
    
    // Check if user has permission on a file
    public static function checkPermission($userId, $filePath, $permission) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT perms FROM users_files_perm WHERE id_user = :userId AND path_file = :filePath");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':filePath', $filePath);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return false;
        }
        
        $perms = $result['perms'];
        
        // Parse permissions string (e.g., "rwxr--r--")
        $userPerms = substr($perms, 0, 3); // User permissions (first 3 characters)
        
        switch ($permission) {
            case self::READ:
                return $userPerms[0] === 'r';
            case self::WRITE:
                return $userPerms[1] === 'w';
            case self::EXECUTE:
                return $userPerms[2] === 'x';
            default:
                return false;
        }
    }
    
    // Set permission for a user on a file
    public static function setPermission($userId, $filePath, $perms) {
        $db = Database::getInstance();
        
        // Check if permission record already exists
        $stmt = $db->prepare("SELECT id FROM users_files_perm WHERE id_user = :userId AND path_file = :filePath");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':filePath', $filePath);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing permission
            $stmt = $db->prepare("UPDATE users_files_perm SET perms = :perms WHERE id_user = :userId AND path_file = :filePath");
        } else {
            // Create new permission
            $stmt = $db->prepare("INSERT INTO users_files_perm (id_user, path_file, perms) VALUES (:userId, :filePath, :perms)");
        }
        
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':filePath', $filePath);
        $stmt->bindParam(':perms', $perms);
        
        return $stmt->execute();
    }
    
    // Convert numeric permissions to string format (e.g., 7 -> "rwx")
    public static function numericToString($numeric) {
        $result = '';
        
        $result .= ($numeric & self::READ) ? 'r' : '-';
        $result .= ($numeric & self::WRITE) ? 'w' : '-';
        $result .= ($numeric & self::EXECUTE) ? 'x' : '-';
        
        return $result;
    }
    
    // Convert string permissions to numeric format (e.g., "rwx" -> 7)
    public static function stringToNumeric($string) {
        $result = 0;
        
        if ($string[0] === 'r') $result |= self::READ;
        if ($string[1] === 'w') $result |= self::WRITE;
        if ($string[2] === 'x') $result |= self::EXECUTE;
        
        return $result;
    }
}

