<?php

namespace App\controllers\dashboardControllers;

use App\models\AdditionalInformation;
use App\core\Validation;
use App\core\Session;
use App\models\User;
use App\models\Doctor;
use PDO;

class AuthController
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
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
        header('Location: ?page=admin_login');
        exit;
    }

    $userModel = new User($this->db);
    $user = $userModel->findByEmail($data['email']);

    if ($user && password_verify($data['password'], $user->getPassword())) {
        if (in_array($user->getRole(), ['admin', 'subadmin'])) {
            $additionalInfo = (new AdditionalInformation($this->db))->getByUserId($user->getId());
            $image = $additionalInfo?->getImage();

            Session::set('admin_user', [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'photo' => $image,
            ]);
            header('Location: ?page=dashboard');
            exit;
        } else {
            Session::set('errors', ['You are not authorized to access the dashboard.']);
            Session::set('old', $data);
            header('Location: ?page=admin_login');
            exit;
        }
    }

    // Check Doctor
    $doctor = Doctor::findByEmail($this->db, $data['email']);
    if ($doctor && password_verify($data['password'], $doctor->getPassword())) {
        Session::set('admin_user', [
            'id' => $doctor->getId(),
            'name' => $doctor->getName(),
            'email' => $doctor->getEmail(),
            'photo' => $doctor->getImage(),
            'role' => 'doctor'
        ]);
        header('Location: ?page=dashboard');
        exit;
    }

    Session::set('errors', ['Invalid email or password.']);
    Session::set('old', $data);
    header('Location: ?page=admin_login');
    exit;
}


    public function logout()
    {
        Session::remove('admin_user');
        Session::set('success', 'Logged out successfully.');
        header('Location: ?page=admin_login');
        exit;
    }
}





