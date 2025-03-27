<?php
class UserModel extends Model {
    protected $table = 'users';
    
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserGroups($userId) {
        $stmt = $this->db->prepare("
            SELECT g.* 
            FROM `groups` g
            JOIN user_groups ug ON g.id = ug.group_id
            WHERE ug.user_id = :userId
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addToGroup($userId, $groupId) {
        $stmt = $this->db->prepare("INSERT INTO user_groups (user_id, group_id) VALUES (:userId, :groupId)");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':groupId', $groupId);
        return $stmt->execute();
    }
    
    public function removeFromGroup($userId, $groupId) {
        $stmt = $this->db->prepare("DELETE FROM user_groups WHERE user_id = :userId AND group_id = :groupId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':groupId', $groupId);
        return $stmt->execute();
    }
}

