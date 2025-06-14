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
    private const UPLOAD_DIR = __DIR__ . '/../../public/uploads/profile_pictures/';

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    private function getAuthenticatedUserId(): ?int
    {
        return Session::get('user')['id'] ?? null;
    }

    public function edit_profile()
    {
        $userId = $this->getAuthenticatedUserId();

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

        $currentUser = $this->userModel->getById($userId);

        $isChanged = (
            $data['name'] !== $currentUser->getName() ||
            $data['email'] !== $currentUser->getEmail() ||
            $data['phone'] !== $currentUser->getPhone() ||
            $data['gender'] !== $currentUser->getGender() ||
            $data['date_of_birth'] !== $currentUser->getDateOfBirth()
        );

        if (!$isChanged) {
            Session::set('info', 'No changes were made.');
            header('Location: ?page=profile');
            exit;
        }

        $updated = $this->userModel->updateUserInfo($userId, $data);

        Session::set($updated ? 'success' : 'errors', $updated ? 'Profile updated successfully.' : 'Update failed.');
        header('Location: ?page=profile');
        exit;
    }

    public function upload_photo()
    {
        $userId = $this->getAuthenticatedUserId();
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

        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $destination = self::UPLOAD_DIR . $fileName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            Session::set('errors', 'Upload failed.');
            header("Location: ?page=upload_photo");
            exit;
        }

        $additionalInfoModel = new AdditionalInformation($this->db);
        $additionalInfo = $additionalInfoModel->getByUserId($userId) ?? new AdditionalInformation($this->db);

        $additionalInfo->setUserId($userId);

        $oldImage = $additionalInfo->getImage();
        if ($oldImage && file_exists(self::UPLOAD_DIR . $oldImage)) {
            unlink(self::UPLOAD_DIR . $oldImage);
        }

        $additionalInfo->setImage($fileName);
        $additionalInfo->setAddress($additionalInfo->getAddress());
        $additionalInfo->save();

        Session::set('success', 'Photo uploaded successfully!');
        header("Location: ?page=profile");
        exit;
    }

    public function delete_photo()
    {
        $userId = $this->getAuthenticatedUserId();

        if (!$userId) {
            Session::set('errors', 'Unauthorized action.');
            header("Location: ?page=profile");
            exit;
        }

        $additionalInfoModel = new AdditionalInformation($this->db);
        $additionalInfo = $additionalInfoModel->getByUserId($userId);

        if (!$additionalInfo || !$additionalInfo->getImage()) {
            Session::set('errors', 'No photo to delete.');
            header("Location: ?page=profile");
            exit;
        }

        $imagePath = self::UPLOAD_DIR . $additionalInfo->getImage();
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $additionalInfo->setImage(null);
        $additionalInfo->setAddress($additionalInfo->getAddress());
        $additionalInfo->save();

        Session::set('success', 'Photo deleted successfully!');
        header("Location: ?page=profile");
        exit;
    }

    public function save_additional_info()
    {
        $userId = $this->getAuthenticatedUserId();

        if (!$userId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('errors', 'Unauthorized action.');
            header('Location: ?page=additional_info');
            exit;
        }

        $address = trim($_POST['address'] ?? '');

        $validator = new Validation();
        $rules = ['address' => ['required', 'string', 'min:5']];

        if (!$validator->validate(['address' => $address], $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', ['address' => $address]);
            header('Location: ?page=additional_info');
            exit;
        }

        $additionalInfoModel = new AdditionalInformation($this->db);
        $additionalInfo = $additionalInfoModel->getByUserId($userId) ?? new AdditionalInformation($this->db);

        $additionalInfo->setUserId($userId);

        if ($additionalInfo->getAddress() === $address) {
            Session::set('info', 'No changes were made.');
            header('Location: ?page=profile');
            exit;
        }

        $additionalInfo->setAddress($address);
        $additionalInfo->setImage($additionalInfo->getImage());
        $additionalInfo->save();

        Session::set('success', 'Address saved successfully.');
        header('Location: ?page=profile');
        exit;
    }
}
