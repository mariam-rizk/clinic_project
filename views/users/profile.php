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


$additionalInfoModel = new AdditionalInformation($db);
$additionalInfoModel->getByUserId($userSession['id']);

$imagePath = 'uploads/profile_pictures/';
$image = $additionalInfoModel->getImage();
$defaultIcon = 'https://cdn-icons-png.flaticon.com/512/149/149071.png';


if ($image && file_exists(__DIR__ . '/../../public/' . $imagePath . $image)) {
    $src = $imagePath . htmlspecialchars($image);
} else {
    $src = $defaultIcon;
}
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Profile</h2>
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="<?= $src ?>" class="img-fluid rounded-circle" alt="Profile Photo"
                 style="width: 200px; height: 200px; object-fit: cover;">
            <div class="mt-3">
                <a href="?page=upload_photo" class="btn btn-success">Upload/Change Photo</a>
            </div>
            <?php if ($image): ?>
                <form method="post" action="?page=delete_photo"
                      onsubmit="return confirm('Are you sure you want to delete your profile photo?')" class="mt-2">
                    <button type="submit" class="btn btn-danger">Delete Photo</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td><?= htmlspecialchars($user->getName()) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($user->getEmail()) ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?= htmlspecialchars($user->getPhone()) ?></td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?= htmlspecialchars($user->getGender()) ?></td>
                </tr>
                <tr>
                    <th>Birth Date</th>
                    <td><?= htmlspecialchars($user->getDateOfBirth()) ?></td>
                </tr>
                <?php if ($additionalInfoModel): ?>
                    <tr>
                        <th>Address</th>
                        <td><?= htmlspecialchars($additionalInfoModel->getAddress() ?? '') ?></td>
                    </tr>
                <?php endif; ?>
            </table>
            <div class="mt-3">
                <a href="?page=edit_profile" class="btn btn-outline-primary">Edit Profile</a>
                <a href="?page=additional_info" class="btn btn-outline-secondary">Additional Info</a>
            </div>
        </div>
    </div>
</div>


