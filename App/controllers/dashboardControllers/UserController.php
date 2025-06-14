<?php

namespace App\controllers\dashboardControllers;

use App\models\User;
use App\core\Session;
use App\core\Validation;
use PDO;

class UserController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }



    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('errors', ['Invalid request.']);
            header('Location: ?page=create_user');
            exit;
        }
    
        $data = [
            'name'       => trim($_POST['name'] ?? ''),
            'email'      => trim($_POST['email'] ?? ''),
            'phone'      => trim($_POST['phone'] ?? ''),
            'password'   => trim($_POST['password'] ?? ''),
            'gender'     => $_POST['gender'] ?? '',
            'birth_date' => $_POST['birth_date'] ?? '',
            'role'       => $_POST['role'] ?? 'user',
        ];
    
        $rules = [
            'name'        => ['required', 'string'],
            'email'       => ['required', 'email'],
            'phone'       => ['required', 'phone'],
            'password'    => ['required', 'min:6'],
            'gender'      => ['required', 'in:male,female'],
            'birth_date'  => ['required', 'date'],
            'role'        => ['in:admin,subadmin,user'],
        ];
    
        $validator = new Validation();
        if (!$validator->validate($data, $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', $data);
            header('Location: ?page=create_user');
            exit;
        }
    
        $userModel = new User($this->db);
    
        if ($userModel->findByEmail($data['email'])) {
            Session::set('errors', ['email' => ['Email already exists.']]);
            header('Location: ?page=create_user');
            exit;
        }
    
        $user = new User($this->db);
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPhone($data['phone']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        $user->setGender($data['gender']);
        $user->setDateOfBirth($data['birth_date']);
        $user->setRole($data['role']);

    
        if ($userModel->create($user)) {
            Session::set('success', 'User created successfully.');
            header('Location: ?page=manage_users');
            exit;
        } else {
            Session::set('errors',  ['Failed to create user.']);
            header('Location: ?page=create_user');
            exit;
        }
    }


    public function Status()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
            Session::set('errors', ['Invalid request.']);
            header('Location: ?page=manage_users');
            exit;
        }
    
        $id = $_POST['id'] ?? null;
        $userModel = new User($this->db);
        $user = $userModel->getById($id);
    
        if (!$user) {
            Session::set('errors', ['User not found.']);
            header('Location: ?page=manage_users');
            exit;
        }
    
      
        if ($user->getRole() === 'admin') {
            Session::set('errors', ['Cannot change status of an admin user.']);
            header('Location: ?page=manage_users');
            exit;
        }
    
        $newStatus = $user->getStatus() === 'active' ? 'inactive' : 'active';
    
        if ($userModel->updateStatus($id, $newStatus)) {
            $message = $newStatus === 'inactive' ? 'User has been blocked.' : 'User has been unblocked.';
            Session::set('success', $message);
        } else {
            Session::set('errors', ['Failed to update user status.']);
        }
    
        header('Location: ?page=manage_users');
        exit;
    }
    

      
     public function updateRole()
    {
        if (!Session::has('user') || Session::get('user')['role'] !== 'admin') {
            Session::set('errors', 'Unauthorized access.');
            header('Location: ?page=dashboard');
            exit;
        }
    
        $id = $_POST['id'] ?? null;
        $newRole = $_POST['role'] ?? null;
        $currentUserId = Session::get('user')['id'];
    
        if (!$id || !$newRole || !in_array($newRole, ['admin', 'subadmin', 'user'])) {
            Session::set('errors', 'Invalid data.');
            header('Location: ?page=manage_users');
            exit;
        }
    
        $userModel = new User($this->db);
        $user = $userModel->getById($id);
    
        if (!$user) {
            Session::set('errors', 'User not found.');
            header('Location: ?page=manage_users');
            exit;
        }
    
        if ($user->getRole() === $newRole) {
            Session::set('info', 'No changes were made.');
            header("Location: ?page=edit_user&id={$id}");
            exit;
        }
    
        $user->setRole($newRole);
        $userModel->updateUserInfo($id, ['role' => $newRole]);
    
      
        if ($id == $currentUserId) {
            Session::remove('admin_user');
            Session::set('info', 'Role updated. Please log in again.');
            header('Location: ?page=login');
            exit;
        }
    
        Session::set('success', 'User role updated successfully.');
        header("Location: ?page=edit_user&id={$id}");
        exit;
    }
    
    

    public function deleteUser(int $id)
    {
        if (!Session::has('user') || Session::get('user')['role'] !== 'admin') {
            Session::set('errors', ['Unauthorized access.']);
            header('Location: ?page=dashboard');
            exit;
        }

        $userModel = new User($this->db);
        $user = $userModel->getById($id);

        if (!$user) {
            Session::set('errors', ['User not found.']);
            header('Location: ?page=manage_users');
            exit;
        }

        if ($user->getRole() === 'admin') {
            Session::set('errors', ['Cannot delete another admin.']);
            header('Location: ?page=manage_users');
            exit;
        }

        if ($userModel->delete($id)) {
            Session::set('success', 'User deleted successfully.');
        } else {
            Session::set('errors', ['Failed to delete user.']);
        }

        header('Location: ?page=manage_users');
        exit;
    }
    



}



