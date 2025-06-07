<?php

namespace App\controllers;

use App\core\Validation;
use App\core\Session;
use App\models\User;
use PDO;

class AuthController
{
    protected PDO $db;
   
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
  

    public function register()
    {
        $data = $_POST;

        $validator = new Validation();
        $rules = [
            'name' => ['required', 'string', 'min:3'],
            'phone' => ['required', 'phone'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
            'confirm_password' => ['required', 'match:password'],
            'gender' => ['required', 'in:Male,Female'],
            'dateofbirth' => ['required', 'date']
        ];

        if (!$validator->validate($data, $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', $data);
            header('Location: ?page=register');
            exit;
        }

     
        $user = new User($this->db);

        if ($user->findByEmail($data['email'])) {
            Session::set('errors', ['email' => ['Email is already registered.']]);
            Session::set('old', $data);
            header('Location: ?page=register');
            exit;
        }

        $user->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'gender' => $data['gender'],
            'dateofbirth' => $data['dateofbirth']
        ]);

        Session::set('success', 'Account created successfully.');
        header('Location: ?page=login');
        exit;
    }


    public function login()
    {
        $data = $_POST;
    
        $validator = new Validation();
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];
    
        if (!$validator->validate($data, $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', $data);
            header('Location: ?page=login');
            exit;
        }
    
        $userModel = new User($this->db);
        $user = $userModel->findByEmail($data['email']);
    
        if (!$user || !password_verify($data['password'], $user['password'])) {
            Session::set('errors', ['Invalid email or password.']);
            Session::set('old', $data);
            header('Location: ?page=login');
            exit;
        }
    
        Session::set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);

        header('Location: ?page=home');
        exit;
    }

    
    public function logout()
    {
        Session::remove('user');
    
        Session::set('success', 'Logged out successfully.');

        header('Location: ?page=login');
        exit;
    }
    
        
    
    
}
    
    