<?php
class UserController extends Controller {
    private $userModel;
    private $groupModel;
    
    public function __construct() {
        $this->requireAdmin();
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }
    
    public function index() {
        $users = $this->userModel->findAll();
        
        $this->view('users/index', [
            'users' => $users
        ]);
    }
    
    public function createForm() {
        $groups = $this->groupModel->findAll();
        
        $this->view('users/create', [
            'groups' => $groups
        ]);
    }
    
    public function create() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;
        $groupIds = $_POST['groups'] ?? [];
        
        if (empty($username) || empty($password)) {
            $this->view('users/create', [
                'error' => 'Please fill in all required fields',
                'groups' => $this->groupModel->findAll()
            ]);
            return;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Create user
        $userId = $this->userModel->create([
            'username' => $username,
            'password' => $hashedPassword,
            'is_admin' => $isAdmin
        ]);
        
        // Add user to groups
        foreach ($groupIds as $groupId) {
            $this->userModel->addToGroup($userId, $groupId);
        }
        
        $this->redirect('/users');
    }
    
    public function editForm($id) {
        $user = $this->userModel->findById($id);
        $groups = $this->groupModel->findAll();
        $userGroups = $this->userModel->getUserGroups($id);
        
        // Extract group IDs for easier comparison
        $userGroupIds = array_map(function($group) {
            return $group['id'];
        }, $userGroups);
        
        $this->view('users/edit', [
            'user' => $user,
            'groups' => $groups,
            'userGroupIds' => $userGroupIds
        ]);
    }
    
    public function update($id) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;
        $groupIds = $_POST['groups'] ?? [];
        
        if (empty($username)) {
            $this->view('users/edit', [
                'error' => 'Username cannot be empty',
                'user' => $this->userModel->findById($id),
                'groups' => $this->groupModel->findAll(),
                'userGroupIds' => array_map(function($group) {
                    return $group['id'];
                }, $this->userModel->getUserGroups($id))
            ]);
            return;
        }
        
        // Update user data
        $data = [
            'username' => $username,
            'is_admin' => $isAdmin
        ];
        
        // Update password if provided
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $this->userModel->update($id, $data);
        
        // Get current user groups
        $userGroups = $this->userModel->getUserGroups($id);
        $currentGroupIds = array_map(function($group) {
            return $group['id'];
        }, $userGroups);
        
        // Add user to new groups
        foreach ($groupIds as $groupId) {
            if (!in_array($groupId, $currentGroupIds)) {
                $this->userModel->addToGroup($id, $groupId);
            }
        }
        
        // Remove user from groups not selected
        foreach ($currentGroupIds as $groupId) {
            if (!in_array($groupId, $groupIds)) {
                $this->userModel->removeFromGroup($id, $groupId);
            }
        }
        
        $this->redirect('/users');
    }
    
    public function delete($id) {
        // Don't allow deleting self
        if ($id == $_SESSION['user_id']) {
            $this->redirect('/users');
            return;
        }
        
        $this->userModel->delete($id);
        $this->redirect('/users');
    }
}

