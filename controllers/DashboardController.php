<?php
class DashboardController extends Controller {
    public function __construct() {
        $this->requireLogin();
    }
    
    public function index() {
        $fileModel = new FileModel();
        $userModel = new UserModel();
        
        $userId = $_SESSION['user_id'];
        $files = $fileModel->getUserFiles($userId);
        $groups = $userModel->getUserGroups($userId);
        
        $this->view('dashboard/index', [
            'files' => $files,
            'groups' => $groups
        ]);
    }
}

