<?php
use App\models\User;
use App\core\Session;

$userSession = Session::get('user');
if (!$userSession) {
    Session::set('errors', 'Unauthorized access.');
    header('Location: ?page=login');
    exit;
}

$userModel = new User($db);
$user = $userModel->getById($userSession['id']);
?>



<div class="container py-5">
    <div class="card shadow-sm p-4">
        <h2 class="text-center mb-4">Edit Your Profile</h2>

        <form action="?page=edit_profile_controller" method="post" class="form" novalidate>

            
            <div class="mb-3">
                <label class="form-label required-label" for="name">Name</label>
                <input type="text" name="name" id="name"
                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['name'] ?? $user?->getName() ?? '') ?>"required>
                <?php if (isset($errors['name'])): ?>
                    <?php foreach ((array)$errors['name'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


           
            <div class="mb-3">
                <label class="form-label required-label" for="email">Email</label>
                <input type="email" name="email" id="email"
                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['email'] ?? $user?->getEmail() ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <?php foreach ((array)$errors['email'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

           
            <div class="mb-3">
                <label class="form-label" for="phone">Phone</label>
                <input type="text" name="phone" id="phone"
                       class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['phone'] ?? $user?->getPhone() ?? '') ?>">
                <?php if (isset($errors['phone'])): ?>
                    <?php foreach ((array)$errors['phone'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            
            <div class="mb-3">
                <label class="form-label required-label" for="gender">Gender</label>
                <select name="gender" id="gender"
                        class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Select Gender</option>
                    <option value="male" <?= ((isset($old['gender']) ? $old['gender'] : $user?->getGender()) === 'male') ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= ((isset($old['gender']) ? $old['gender'] : $user?->getGender()) === 'female') ? 'selected' : '' ?>>Female</option>
                </select>
                <?php if (isset($errors['gender'])): ?>
                    <?php foreach ((array)$errors['gender'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

          
            <div class="mb-3">
                <label class="form-label required-label" for="date_of_birth">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth"
                       class="form-control <?= isset($errors['date_of_birth']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($old['date_of_birth'] ?? $user?->getDateOfBirth() ?? '') ?>" required>
                <?php if (isset($errors['date_of_birth'])): ?>
                    <?php foreach ((array)$errors['date_of_birth'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
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
