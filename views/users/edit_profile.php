<?php
use App\core\Session;
use App\models\User;

$userId = Session::get('user')['id'];
$userModel = new User($db);
$user = $userModel->getById($userId);



?>

<div class="container py-5">
    <div class="card shadow-sm p-4">
        <h2 class="text-center mb-4">Edit Your Profile</h2>

        <form action="?page=edit_profile_controller" method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['name'] ?? $user['name'] ?? '') ?>" required>
                <?php if (!empty($errors['name'])): ?>
                    <?php foreach ($errors['name'] as $err): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '') ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <?php foreach ($errors['email'] as $err): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" name="phone" id="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>">
                <?php if (!empty($errors['phone'])): ?>
                    <?php foreach ($errors['phone'] as $err): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Gender:</label>
                <select name="gender" id="gender" class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>">
                    <option value="">Select Gender</option>
                    <option value="male" <?= ((isset($old['gender']) ? $old['gender'] : $user['gender']) === 'male') ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= ((isset($old['gender']) ? $old['gender'] : $user['gender']) === 'female') ? 'selected' : '' ?>>Female</option>
                </select>
                <?php if (!empty($errors['gender'])): ?>
                    <?php foreach ($errors['gender'] as $err): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Date of Birth:</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control <?= isset($errors['date_of_birth']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['date_of_birth'] ?? $user['date_of_birth'] ?? '') ?>">
                <?php if (!empty($errors['date_of_birth'])): ?>
                    <?php foreach ($errors['date_of_birth'] as $err): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </form>

        <div class="mt-3 text-center">
            <a href="?page=profile" class="btn btn-secondary">Back to Profile</a>
        </div>
    </div>
</div>
