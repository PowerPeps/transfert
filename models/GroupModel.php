<?php
class GroupModel extends Model {
    protected $table = '`groups`';
    
    public function getGroupUsers($groupId) {
        $stmt = $this->db->prepare("
            SELECT u.* 
            FROM users u
            JOIN user_groups ug ON u.id = ug.user_id
            WHERE ug.group_id = :groupId
        ");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getGroupFiles($groupId) {
        $stmt = $this->db->prepare("
            SELECT f.* 
            FROM files f
            JOIN group_files_perm gfp ON f.path = gfp.path_file
            WHERE gfp.group_id = :groupId
        ");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function setFilePermission($groupId, $filePath, $perms) {
        // Check if permission record already exists
        $stmt = $this->db->prepare("SELECT id FROM group_files_perm WHERE group_id = :groupId AND path_file = :filePath");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->bindParam(':filePath', $filePath);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing permission
            $stmt = $this->db->prepare("UPDATE group_files_perm SET perms = :perms WHERE group_id = :groupId AND path_file = :filePath");
        } else {
            // Create new permission
            $stmt = $this->db->prepare("INSERT INTO group_files_perm (group_id, path_file, perms) VALUES (:groupId, :filePath, :perms)");
        }
        
        $stmt->bindParam(':groupId', $groupId);
        $stmt->bindParam(':filePath', $filePath);
        $stmt->bindParam(':perms', $perms);
        
        return $stmt->execute();
    }
    
    public function getGroupFilePermission($groupId, $filePath) {
        $stmt = $this->db->prepare("SELECT perms FROM group_files_perm WHERE group_id = :groupId AND path_file = :filePath");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->bindParam(':filePath', $filePath);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['perms'] : null;
    }
    
    public function getGroupsForFile($filePath) {
        $stmt = $this->db->prepare("
            SELECT g.*, gfp.perms 
            FROM `groups` g
            JOIN group_files_perm gfp ON g.id = gfp.group_id
            WHERE gfp.path_file = :filePath
        ");
        $stmt->bindParam(':filePath', $filePath);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPublicGroups() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE is_public = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getGroupStats($groupId) {
        // Nombre de membres
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as member_count 
            FROM user_groups 
            WHERE group_id = :groupId
        ");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->execute();
        $memberCount = $stmt->fetch(PDO::FETCH_ASSOC)['member_count'];
        
        // Nombre de fichiers
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as file_count 
            FROM group_files_perm 
            WHERE group_id = :groupId
        ");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->execute();
        $fileCount = $stmt->fetch(PDO::FETCH_ASSOC)['file_count'];
        
        return [
            'member_count' => $memberCount,
            'file_count' => $fileCount
        ];
    }
}

