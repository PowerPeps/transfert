<?php
class FileController extends Controller {
    private $fileModel;

    public function __construct() {
        $this->requireLogin();
        $this->fileModel = new FileModel();
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $files = $this->fileModel->getUserFiles($userId);
        
        $this->view('files/index', [
            'files' => $files
        ]);
    }

    public function createForm() {
        $this->view('files/create');
    }

    public function create() {
        $userId = $_SESSION['user_id'];
        
        // Gérer le téléchargement de fichier
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->view('files/create', ['error' => 'Le téléchargement du fichier a échoué']);
            return;
        }
        
        $file = $_FILES['file'];
        
        // Vérifier la taille du fichier
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            $this->view('files/create', ['error' => 'La taille du fichier dépasse la limite']);
            return;
        }
        
        // Générer un nom de fichier unique
        $filename = uniqid() . '_' . basename($file['name']);
        $uploadPath = UPLOAD_DIR . $filename;
        
        // Déplacer le fichier téléchargé
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $this->view('files/create', ['error' => 'Échec de l\'enregistrement du fichier']);
            return;
        }
        
        // Obtenir les paramètres d'expiration
        $expirationDate = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
        $expirationDownloads = !empty($_POST['expiration_downloads']) ? (int)$_POST['expiration_downloads'] : null;
        
        // Créer l'enregistrement du fichier
        $uuid = $this->fileModel->createFile($uploadPath, $expirationDate, $expirationDownloads);
        
        // Définir les permissions
        $perms = $_POST['permissions'] ?? 'rwx------';
        Permissions::setPermission($userId, $uploadPath, $perms);
        
        $this->redirect('/files');
    }

    public function editForm($id) {
        $userId = $_SESSION['user_id'];
        $id = urldecode($id); // Décoder l'ID qui est un chemin de fichier
        $file = $this->fileModel->findById($id);
        if (!$file || !Permissions::checkPermission($userId, $file['path'], Permissions::WRITE)) {
            $this->redirect('/files');
            return;
        }
        
        // Récupérer les permissions actuelles
        $stmt = Database::getInstance()->prepare("SELECT perms FROM users_files_perm WHERE id_user = :userId AND path_file = :filePath");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':filePath', $file['path']);
        $stmt->execute();
        $permResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $file['perms'] = $permResult ? $permResult['perms'] : 'rwx------';
        
        // Récupérer les groupes de l'utilisateur
        $userModel = new UserModel();
        $groups = $userModel->getUserGroups($userId);
        
        // Récupérer les groupes avec lesquels le fichier est partagé
        $groupModel = new GroupModel();
        $sharedGroups = $groupModel->getGroupsForFile($file['path']);
        
        $this->view('files/edit', [
            'file' => $file,
            'groups' => $groups,
            'sharedGroups' => $sharedGroups
        ]);
    }

    public function update($id) {
        $userId = $_SESSION['user_id'];
        $id = urldecode($id); // Décoder l'ID qui est un chemin de fichier
        $file = $this->fileModel->findById($id);
        
        if (!$file || !Permissions::checkPermission($userId, $file['path'], Permissions::WRITE)) {
            $this->redirect('/files');
            return;
        }
        
        // Mettre à jour les paramètres d'expiration
        $expirationDate = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
        $expirationDownloads = !empty($_POST['expiration_downloads']) ? (int)$_POST['expiration_downloads'] : null;
        
        $data = [
            'expiration_date' => $expirationDate,
            'expiration_nb_download' => $expirationDownloads
        ];
        
        $this->fileModel->update($file['id'], $data);
        
        // Mettre à jour les permissions
        if (isset($_POST['permissions'])) {
            Permissions::setPermission($userId, $file['path'], $_POST['permissions']);
        }
        
        // Mettre à jour les partages de groupe
        if (isset($_POST['share_groups']) && is_array($_POST['share_groups'])) {
            $groupModel = new GroupModel();
            $groupIds = $_POST['share_groups'];
            $groupPerms = $_POST['group_perms'] ?? [];
            
            foreach ($groupIds as $index => $groupId) {
                $perms = isset($groupPerms[$index]) ? $groupPerms[$index] : 'r--';
                $groupModel->setFilePermission($groupId, $file['path'], $perms);
            }
            
            // Supprimer les partages pour les groupes non sélectionnés
            $sharedGroups = $groupModel->getGroupsForFile($file['path']);
            foreach ($sharedGroups as $group) {
                if (!in_array($group['id'], $groupIds)) {
                    $stmt = Database::getInstance()->prepare("DELETE FROM group_files_perm WHERE group_id = :groupId AND path_file = :filePath");
                    $stmt->bindParam(':groupId', $group['id']);
                    $stmt->bindParam(':filePath', $file['path']);
                    $stmt->execute();
                }
            }
        }
        
        $this->redirect('/files');
    }

    public function delete($id) {
        $userId = $_SESSION['user_id'];
        $file = $this->fileModel->findById($id);
        var_dump($file);
        if (!$file || !Permissions::checkPermission($userId, $file['path'], Permissions::WRITE)) {
            $this->redirect('/files');
            return;
        }
        
        // Supprimer le fichier du disque
        if (file_exists($file['path'])) {
            unlink($file['path']);
        }
        
        // Supprimer l'enregistrement du fichier
        $this->fileModel->delete($file['id']);
        
        $this->redirect('/files');
    }

    public function download($uuid) {
        $file = $this->fileModel->findByUuid($uuid);
        
        if (!$file || !file_exists($file['path'])) {
            header("HTTP/1.0 404 Not Found");
            echo '404 Not Found';
            return;
        }
        
        // Vérifier si le fichier a expiré
        if ($this->fileModel->isExpired($file)) {
            header("HTTP/1.0 410 Gone");
            echo 'Le fichier a expiré';
            return;
        }
        
        // Si l'utilisateur est connecté, vérifier les permissions
        if ($this->isLoggedIn()) {
            $userId = $_SESSION['user_id'];
            $hasPermission = Permissions::checkPermission($userId, $file['path'], Permissions::EXECUTE);
            
            // Si l'utilisateur n'a pas de permission directe, vérifier les permissions de groupe
            if (!$hasPermission) {
                $userModel = new UserModel();
                $groups = $userModel->getUserGroups($userId);
                
                foreach ($groups as $group) {
                    $stmt = Database::getInstance()->prepare("
                        SELECT perms 
                        FROM group_files_perm 
                        WHERE group_id = :groupId AND path_file = :filePath
                    ");
                    $stmt->bindParam(':groupId', $group['id']);
                    $stmt->bindParam(':filePath', $file['path']);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result && strpos($result['perms'], 'x') !== false) {
                        $hasPermission = true;
                        break;
                    }
                }
            }
            
            if (!$hasPermission) {
                header("HTTP/1.0 403 Forbidden");
                echo 'Accès refusé';
                return;
            }
        }
        
        // Mettre à jour le compteur de téléchargements si nécessaire
        if ($file['expiration_nb_download'] !== null) {
            $this->fileModel->updateDownloadCount($file['path']);
        }
        
        // Envoyer le fichier au navigateur
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file['path']) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file['path']));
        readfile($file['path']);
        exit;
    }
}

