<?php
namespace App\controllers;

use PDO;
use App\core\Session;
use App\core\Validation;
use App\models\User;
use App\models\AdditionalInformation;


class ProfileController
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function upload_photo()
    {
        $userId = Session::get('user')['id'] ?? null;
        if (!$userId) {
            header("Location: ?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $file = $_FILES['profile_image']; 

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($ext), $allowed)) {
                $newName = uniqid('profile_', true) . '.' . $ext;
                $uploadDir = __DIR__ . '/../../public/uploads/profile_pictures/';
                $uploadPath = $uploadDir . $newName;

              
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $infoModel = new AdditionalInformation($this->db);

                 
                    $oldImage = $infoModel->getProfilePicture($userId);
                    if ($oldImage && file_exists($uploadDir . $oldImage)) {
                        unlink($uploadDir . $oldImage);
                    }

                    $infoModel->updateProfilePicture($userId, $newName);

                    Session::set('success', 'profile picture uploaded successfully.');
                } else {
                    Session::set('error', 'failed to upload the image. Please try again.');
                }
            } else {
                Session::set('error', 'please select a valid image file (jpg, jpeg, png, gif) to upload.');
            }
        } else {
            Session::set('error', 'failed to upload the image. Please check the file and try again.');
        }

        header('Location: ?page=profile');
        exit;
    }

    public function delete_photo()
    {
        $userId = Session::get('user')['id'] ?? null;
        if (!$userId) {
            header("Location: ?page=login");
            exit;
        }

        $infoModel = new AdditionalInformation($this->db);
        $uploadDir = __DIR__ . '/../../public/uploads/profile_pictures/';

        $oldImage = $infoModel->getProfilePicture($userId);
        if ($oldImage && file_exists($uploadDir . $oldImage)) {
            unlink($uploadDir . $oldImage);
        }

        $infoModel->updateProfilePicture($userId, null);

        Session::set('success', 'profile picture deleted successfully.');
        header('Location: ?page=profile');
        exit;
    }

    public function additionalInfo()
    {
        $userId = Session::get('user')['id'];
        $address = trim($_POST['address'] ?? '');
    
       
        $validator = new Validation();
        $data = ['address' => $address];
        $rules = ['address' => ['required', 'string', 'min:5']];
    
        if (!$validator->validate($data, $rules)) {
            Session::set('errors', $validator->getErrors());
            header('Location: ?page=additional_info');
            exit;
        }

        $infoModel = new AdditionalInformation($this->db);
        if ($infoModel->updateAddress($userId, $address)) {
            Session::set('success', 'Address saved successfully.');
        } else {
            Session::set('errors', ['address' => ['Failed to save address. Please try again.']]);
        }
    
        header('Location: ?page=profile');
        exit;
    }


    public function editProfile()
    {
        $userId = Session::get('user')['id'] ?? null;
        if (!$userId) {
            header("Location: ?page=login");
            exit;
        }
    
        $validator = new Validation();
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'gender' => $_POST['gender'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? '',
        ];
    
        $rules = [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'phone'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date'],
        ];
    
        if (!$validator->validate($data, $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', $data);
            header('Location: ?page=edit_profile');
            exit;
        }
    
        $userModel = new User($this->db);
        $oldUser = $userModel->getById($userId);
    
       
        $dataChanged = false;
        foreach ($data as $key => $value) {
            if ($oldUser[$key] != $value) {
                $dataChanged = true;
                break;
            }
        }
    
        if ($dataChanged) {
           
            $userModel->updateUserInfo($userId, $data);
            $updatedUser = $userModel->getById($userId);
            Session::set('user', $updatedUser);
            Session::set('success', 'Profile updated successfully.');
        } else {
          
            Session::set('info', 'No changes were made.');
                header('Location: ?page=edit_profile');
                exit;
        }
    
        header('Location: ?page=profile');
        exit;
    }
    
    
    

}


    
