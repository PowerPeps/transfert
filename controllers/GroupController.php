<?php
class GroupController extends Controller {
    private $groupModel;
    private $userModel;
    private $fileModel;

    public function __construct() {
        $this->requireLogin(); // Tous les utilisateurs peuvent accéder à la gestion des groupes
        $this->groupModel = new GroupModel();
        $this->userModel = new UserModel();
        $this->fileModel = new FileModel();
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
        
        // Les administrateurs voient tous les groupes, les utilisateurs normaux voient leurs groupes
        if ($isAdmin) {
            $groups = $this->groupModel->findAll();
        } else {
            $groups = $this->userModel->getUserGroups($userId);
        }
        
        // Récupérer les groupes disponibles pour rejoindre (pour les utilisateurs normaux)
        $availableGroups = [];
        if (!$isAdmin) {
            $allGroups = $this->groupModel->findAll();
            $userGroupIds = array_map(function($group) {
                return $group['id'];
            }, $groups);
            
            foreach ($allGroups as $group) {
                if (!in_array($group['id'], $userGroupIds) && $group['is_public']) {
                    $availableGroups[] = $group;
                }
            }
        }
        
        $this->view('groups/index', [
            'groups' => $groups,
            'availableGroups' => $availableGroups,
            'groupModel' => $this->groupModel,
            'isAdmin' => $isAdmin
        ]);
    }

    public function createForm() {
        $this->view('groups/create', [
            'isAdmin' => isset($_SESSION['is_admin']) && $_SESSION['is_admin']
        ]);
    }

    public function create() {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $isPublic = isset($_POST['is_public']) ? 1 : 0;
        
        if (empty($name)) {
            $this->view('groups/create', [
                'error' => 'Le nom du groupe ne peut pas être vide',
                'isAdmin' => isset($_SESSION['is_admin']) && $_SESSION['is_admin']
            ]);
            return;
        }
        
        // Créer le groupe
        $groupId = $this->groupModel->create([
            'name' => $name,
            'description' => $description,
            'is_public' => $isPublic,
            'created_by' => $_SESSION['user_id']
        ]);
        
        // Ajouter le créateur comme membre du groupe
        $this->userModel->addToGroup($_SESSION['user_id'], $groupId);
        
        $this->redirect('/groups');
    }

    public function editForm($id) {
        $userId = $_SESSION['user_id'];
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
        $group = $this->groupModel->findById($id);
        
        // Vérifier si l'utilisateur a le droit de modifier ce groupe
        if (!$isAdmin && $group['created_by'] != $userId) {
            $this->redirect('/groups');
            return;
        }
        
        $users = $this->userModel->findAll();
        $groupUsers = $this->groupModel->getGroupUsers($id);
        $files = $this->fileModel->getUserFiles($userId);
        $groupFiles = $this->groupModel->getGroupFiles($id);
        
        // Extraire les IDs des utilisateurs pour une comparaison plus facile
        $groupUserIds = array_map(function($user) {
            return $user['id'];
        }, $groupUsers);
        
        // Extraire les chemins des fichiers pour une comparaison plus facile
        $groupFilePaths = array_map(function($file) {
            return $file['path'];
        }, $groupFiles);
        
        $this->view('groups/edit', [
            'group' => $group,
            'users' => $users,
            'groupUserIds' => $groupUserIds,
            'files' => $files,
            'groupFilePaths' => $groupFilePaths,
            'isAdmin' => $isAdmin,
            'isCreator' => $group['created_by'] == $userId
        ]);
    }

    public function update($id) {
        $userId = $_SESSION['user_id'];
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
        $group = $this->groupModel->findById($id);
        
        // Vérifier si l'utilisateur a le droit de modifier ce groupe
        if (!$isAdmin && $group['created_by'] != $userId) {
            $this->redirect('/groups');
            return;
        }
        
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $isPublic = isset($_POST['is_public']) ? 1 : 0;
        $userIds = $_POST['users'] ?? [];
        $filePaths = $_POST['files'] ?? [];
        $filePerms = $_POST['file_perms'] ?? [];
        
        if (empty($name)) {
            $this->redirect('/groups/edit/' . $id);
            return;
        }
        
        // Mettre à jour les informations du groupe
        $this->groupModel->update($id, [
            'name' => $name,
            'description' => $description,
            'is_public' => $isPublic
        ]);
        
        // Si administrateur ou créateur, mettre à jour les membres
        if ($isAdmin || $group['created_by'] == $userId) {
            // Mettre à jour les utilisateurs dans le groupe
            $groupUsers = $this->groupModel->getGroupUsers($id);
            $currentUserIds = array_map(function($user) {
                return $user['id'];
            }, $groupUsers);
            
            // Ajouter des utilisateurs au groupe
            foreach ($userIds as $userId) {
                if (!in_array($userId, $currentUserIds)) {
                    $this->userModel->addToGroup($userId, $id);
                }
            }
            
            // Retirer des utilisateurs du groupe (sauf le créateur)
            foreach ($currentUserIds as $currentUserId) {
                if (!in_array($currentUserId, $userIds) && $currentUserId != $group['created_by']) {
                    $this->userModel->removeFromGroup($currentUserId, $id);
                }
            }
        }
        
        // Mettre à jour les permissions des fichiers
        if (!empty($filePaths)) {
            foreach ($filePaths as $index => $filePath) {
                if (isset($filePerms[$index])) {
                    $this->groupModel->setFilePermission($id, $filePath, $filePerms[$index]);
                }
            }
        }
        
        $this->redirect('/groups');
    }

    public function delete($id) {
        $userId = $_SESSION['user_id'];
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
        $group = $this->groupModel->findById($id);
        
        // Vérifier si l'utilisateur a le droit de supprimer ce groupe
        if (!$isAdmin && $group['created_by'] != $userId) {
            $this->redirect('/groups');
            return;
        }
        
        $this->groupModel->delete($id);
        $this->redirect('/groups');
    }
    
    public function join($id) {
        $userId = $_SESSION['user_id'];
        $group = $this->groupModel->findById($id);
        
        // Vérifier si le groupe existe et est public
        if (!$group || !$group['is_public']) {
            $this->redirect('/groups');
            return;
        }
        
        // Vérifier si l'utilisateur est déjà membre du groupe
        $groupUsers = $this->groupModel->getGroupUsers($id);
        $groupUserIds = array_map(function($user) {
            return $user['id'];
        }, $groupUsers);
        
        if (!in_array($userId, $groupUserIds)) {
            $this->userModel->addToGroup($userId, $id);
        }
        
        $this->redirect('/groups');
    }
    
    public function leave($id) {
        $userId = $_SESSION['user_id'];
        $group = $this->groupModel->findById($id);
        
        // Vérifier si le groupe existe
        if (!$group) {
            $this->redirect('/groups');
            return;
        }
        
        // Le créateur ne peut pas quitter son propre groupe
        if ($group['created_by'] == $userId) {
            $this->redirect('/groups');
            return;
        }
        
        $this->userModel->removeFromGroup($userId, $id);
        $this->redirect('/groups');
    }
}

