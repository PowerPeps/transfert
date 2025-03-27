<?php
class FileModel extends Model {
    protected $table = 'files';

    public function findByUuid($uuid) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE uuid = :uuid");
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPath($path) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE path = :path");
        $stmt->bindParam(':path', $path);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserFiles($userId) {
        $stmt = $this->db->prepare("
            SELECT f.*, ufp.perms 
            FROM files f
            JOIN users_files_perm ufp ON f.path = ufp.path_file
            WHERE ufp.id_user = :userId
            ORDER BY f.created_at DESC
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createFile($path, $expirationDate = null, $expirationDownloads = null) {
        $uuid = $this->generateUuid();
        $fileSize = file_exists($path) ? filesize($path) : 0;
        
        $stmt = $this->db->prepare("
            INSERT INTO files (path, expiration_date, expiration_nb_download, uuid, size) 
            VALUES (:path, :expirationDate, :expirationDownloads, :uuid, :size)
        ");
        
        $stmt->bindParam(':path', $path);
        $stmt->bindParam(':expirationDate', $expirationDate);
        $stmt->bindParam(':expirationDownloads', $expirationDownloads);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->bindParam(':size', $fileSize);
        
        $stmt->execute();
        
        return $uuid;
    }

    public function update($id, $data) {
        $setClause = '';
        foreach (array_keys($data) as $key) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ', ');
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $setClause WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute();
    }

    public function delete($id) {
        // Supprimer d'abord les permissions associées
        $file = $this->findById($id);
        if ($file) {
            $stmt = $this->db->prepare("DELETE FROM users_files_perm WHERE path_file = :path");
            $stmt->bindParam(':path', $file['path']);
            $stmt->execute();
            
            $stmt = $this->db->prepare("DELETE FROM group_files_perm WHERE path_file = :path");
            $stmt->bindParam(':path', $file['path']);
            $stmt->execute();
        }
        
        // Puis supprimer le fichier
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updateDownloadCount($path) {
        $stmt = $this->db->prepare("
            UPDATE files 
            SET expiration_nb_download = expiration_nb_download - 1 
            WHERE path = :path AND expiration_nb_download > 0
        ");
        $stmt->bindParam(':path', $path);
        return $stmt->execute();
    }

    public function isExpired($file) {
        // Vérifier la date d'expiration
        if ($file['expiration_date'] !== null && strtotime($file['expiration_date']) < time()) {
            return true;
        }
        
        // Vérifier le compteur de téléchargements
        if ($file['expiration_nb_download'] !== null && $file['expiration_nb_download'] <= 0) {
            return true;
        }
        
        return false;
    }

    private function generateUuid() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

