<?php

namespace App\controllers;

use App\core\Session;
use App\core\Validation;
use App\models\User;
use App\models\AdditionalInformation;
use PDO;

class ProfileController
{
    private PDO $db;
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }
    public function edit_profile()
    {
        $userId = Session::get('user')['id'] ?? null;
        if (!$userId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('errors', 'Unauthorized action.');
            header('Location: ?page=edit_profile');
            exit;
        }
    
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'gender' => $_POST['gender'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? ''
        ];
    
        $rules = [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'phone'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date']
        ];
    
        $validator = new Validation();
    
        if (!$validator->validate($data, $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', $data);
            header('Location: ?page=edit_profile');
            exit;
        }
    
        $updated = $this->userModel->updateUserInfo($userId, $data);
    
    
        if ($updated) {
            Session::set('success', 'Profile updated successfully.');
        } else {
            if (!Session::get('errors')) {
                Session::set('info', 'No changes were made or update failed.');
            }
            Session::set('old', $data); 
            header('Location: ?page=edit_profile');
            exit;
        }
    
        header('Location: ?page=profile');
        exit;
    }

    public function upload_photo()
    {
        $userId = Session::get('user')['id'] ?? null;
        if (!$userId) {
            Session::set('errors', 'Unauthorized action.');
            header("Location: ?page=upload_photo");
            exit;
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            Session::set('errors', 'Please select a valid photo to upload.');
            header("Location: ?page=upload_photo");
            exit;
        }

        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $uploadDir = __DIR__ . '../../../public/uploads/profile_pictures/';
        $destination = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            Session::set('errors', 'Upload failed.');
            header("Location: ?page=upload_photo");
            exit;
        }

        $additionalInfo = new AdditionalInformation($this->db);
        $additionalInfo->getByUserId($userId);

        $oldImage = $additionalInfo->getImage();
        if ($oldImage && file_exists($uploadDir . $oldImage)) {
            unlink($uploadDir . $oldImage);
        }

        $additionalInfo->setUserId($userId);
        $additionalInfo->setImage($fileName);
        $additionalInfo->save();

        Session::set('success', 'Photo uploaded successfully!');
        header("Location: ?page=profile");
        exit;
    }

    public function delete_photo()
    {
        $userId = Session::get('user')['id'] ?? null;

        $additionalInfo = new AdditionalInformation($this->db);
        $additionalInfo->getByUserId($userId);

        $image = $additionalInfo->getImage();
        $uploadPath = __DIR__ . "../../../public/uploads/profile_pictures/";

        if ($image && file_exists($uploadPath . $image)) {
            unlink($uploadPath . $image);
        }

        $additionalInfo->setImage(null);
        $additionalInfo->save();

        Session::set('success', 'Photo deleted successfully!');
        header("Location: ?page=profile");
        exit;
    }
    public function save_additional_info()
    {
        $userId = Session::get('user')['id'] ?? null;
    
        if (!$userId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('errors', 'Unauthorized action.');
            header('Location: ?page=additional_info');
            exit;
        }
    
        $address = trim($_POST['address'] ?? '');
    
        $validator = new Validation();
    
        $rules = [
            'address' => ['required', 'string', 'min:5'],
        ];
    
        if (!$validator->validate(['address' => $address], $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', ['address' => $address]);
            header('Location: ?page=additional_info');
            exit;
        }
    
        $additionalInfo = new AdditionalInformation($this->db);
        $additionalInfo->getByUserId($userId);
    
        $oldAddress = $additionalInfo->getAddress();
    
    
        if ($oldAddress === $address) {
            Session::set('info', 'No changes were made.');
            header('Location: ?page=profile');
            exit;
        }
    
        $additionalInfo->setUserId($userId);
        $additionalInfo->setAddress($address);
    
        if ($additionalInfo->save()) {
            Session::set('success', 'Address saved successfully.');
        } else {
            Session::set('errors', 'Failed to save address.');
        }
    
        header('Location: ?page=profile');
        exit;
    }



}
