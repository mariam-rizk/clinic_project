<?php
use App\core\Session;
use App\models\AdditionalInformation;

$user = Session::get('user');
if (!$user || !isset($user['id'])) {
    header('Location: ?page=login');
    exit;
}

$userId = $user['id'];

$infoModel = new AdditionalInformation($db);
$currentImage = $infoModel->getProfilePicture($userId);
?>

<div class="container py-5">
    <div class="card shadow-sm p-4">
        <h2 class="text-center mb-4">Update Profile Picture</h2>

        <?php if ($msg = Session::get('success')): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
            <?php Session::remove('success'); ?>
        <?php endif; ?>

        <?php if ($msg = Session::get('error')): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
            <?php Session::remove('error'); ?>
        <?php endif; ?>

        <div class="text-center mb-4">
            <?php if ($currentImage): ?>
                <img src="uploads/profile_pictures/<?= htmlspecialchars($currentImage) ?>" alt="Profile Picture"
                     class="rounded-circle border shadow-sm" style="width:150px;height:150px;object-fit:cover;">
                <form action="?page=delete_photo" method="post" style="margin-top: 10px;">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete the photo?');"
                            class="btn btn-danger btn-sm">Delete Photo</button>
                </form>
            <?php else: ?>
                <p class="text-muted">No profile picture uploaded yet.</p>
            <?php endif; ?>
        </div>

        <form action="?page=upload_photo_controller" method="post" enctype="multipart/form-data" class="text-center">
            <div class="mb-3">
                <label for="profile_image" class="form-label">Choose a new image:</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Photo</button>
        </form>
    </div>
</div>
