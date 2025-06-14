<?php

use App\models\User;
use App\models\AdditionalInformation;
use App\core\Session;

$userSession = Session::get('user');
if (!$userSession) {
    Session::set('errors', 'Unauthorized access.');
    header('Location: ?page=login');
    exit;
}

$userModel = new User($db);
$user = $userModel->getById($userSession['id']);

$additionalInfo = new AdditionalInformation($db);
$additionalInfo->getByUserId($userSession['id']);


$imagePath = 'uploads/profile_pictures/';
$defaultIcon = "https://cdn-icons-png.flaticon.com/512/847/847969.png";

$image = null;
if (isset($additionalInfo) && $additionalInfo instanceof \App\models\AdditionalInformation) {
    $image = $additionalInfo->getImage();
}

if ($image && file_exists(__DIR__ . '/../../public/' . $imagePath . $image)) {
    $src = $imagePath . htmlspecialchars($image);
} else {
    $src = $defaultIcon;
}
?>



<div class="container my-5">
    <h2 class="mb-4 text-center">Upload Profile Photo</h2>


    <div class="text-center mb-4">
        <img src="<?= $src ?>" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Photo">
    </div>

    <form method="post" enctype="multipart/form-data" action="?page=upload_photo_controller" class="w-50 mx-auto" novalidate>
        <div class="mb-3">
            <label for="photo" class="form-label">Choose Image</label>
            <input class="form-control" type="file" id="photo" name="photo" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Upload</button>
            <a href="?page=profile" class="btn btn-secondary">Back to Profile</a>
        </div>
    </form>
</div>

