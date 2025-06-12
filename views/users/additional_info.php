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
$additionalInfo->loadByUserId($userSession['id']);

$info = $additionalInfo;

?>

<div class="container py-5">
    <div class="card shadow-sm p-4">
        <h2 class="text-center mb-4">Additional Information</h2>

        <form action="?page=additional_info_controller" method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea name="address" id="address" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" rows="3" required><?= htmlspecialchars($old['address'] ?? $info?->getAddress() ?? '') ?></textarea>
                <?php if (!empty($errors['address'])): ?>
                    <?php foreach ((array)$errors['address'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100">Save Address</button>
        </form>

        <div class="mt-3 text-center">
            <a href="?page=profile" class="btn btn-secondary">Back to Profile</a>
        </div>
    </div>
</div>

