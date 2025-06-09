<?php
use App\models\User;
use App\models\AdditionalInformation;
use App\core\Session;

$userId = Session::get('user')['id'];
$userModel = new User($db);
$infoModel = new AdditionalInformation($db);

$user = $userModel->getById($userId);
$additionalInfo = $infoModel->getByUserId($userId);


$profileImage = $additionalInfo['image'] ?? null;
?>

<div class="container py-5">
    <h1 class="text-center mb-4">Your Profile</h1>

    <div class="card shadow p-4">
        <div class="text-center mb-4">
            <?php if (!empty($profileImage)): ?>
                
                <img src="uploads/profile_pictures/<?= htmlspecialchars($additionalInfo['image'] ) ?>" alt="Profile Image"
                     class="rounded-circle" style="width:150px;height:150px;object-fit:cover;">

            <?php else: ?>
                <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y"
                     alt="Default Avatar" class="rounded-circle" style="width:150px;height:150px;object-fit:cover;">
            <?php endif; ?>

            <div class="mt-3">
                <a href="?page=upload_photo" class="btn btn-secondary">Upload Photo</a>
            </div>
        </div>

        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($user['name'] ?? '') ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></li>
            <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? '') ?></li>
            <li class="list-group-item"><strong>Gender:</strong> <?= htmlspecialchars($user['gender'] ?? '') ?></li>
            <li class="list-group-item"><strong>Date of Birth:</strong> <?= htmlspecialchars($user['date_of_birth'] ?? '') ?></li>
            <li class="list-group-item"><strong>Role:</strong> <?= htmlspecialchars($user['role'] ?? 'user') ?></li>
            <li class="list-group-item"><strong>Registered At:</strong> <?= htmlspecialchars($user['created_at'] ?? '') ?></li>
        </ul>

        <?php if (!empty($additionalInfo['address'])): ?>
            <div class="mt-3">
                <h5>Additional Information</h5>
                <p><strong>Address:</strong> <?= htmlspecialchars($additionalInfo['address']) ?></p>
            </div>
        <?php endif; ?>

        <div class="mt-3 d-flex flex-wrap gap-2">
            <a href="?page=edit_profile" class="btn btn-primary">Edit Profile</a>
            <?php if(!empty($additionalInfo['address'])):?> 
            <a href="?page=additional_info" class="btn btn-secondary">Edit Address</a>
            <?php else: ?>
            <a href="?page=additional_info" class="btn btn-info">Add Address</a>
            <?php endif; ?>
            
        </div>
    </div>
</div>


