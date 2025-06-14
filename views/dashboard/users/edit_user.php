<?php
use App\models\User;
use App\models\AdditionalInformation;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Invalid user ID');
}

$userModel = new User($db);
$user = $userModel->getById((int)$_GET['id']);
if (!$user) {
    exit('User not found');
}


$infoModel = new AdditionalInformation($db);
$info = $infoModel->getByUserId($user->getId());


$currentUserRole = $_SESSION['user']['role'] ?? 'user';


?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="?page=manage_users" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user->getName()) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user->getEmail()) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Phone</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user->getPhone()) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Gender</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user->getGender()) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Date of Birth</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user->getDateOfBirth()) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Status</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user->getStatus()) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Role</label>
                                <?php if ($_SESSION['admin_user']['role']=='admin'): ?>
                                    <form action="?page=update_role" method="POST" class="d-flex gap-2">
                                        <select name="role" class="form-control" required>
                                            <option value="admin" <?= $user->getRole() === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="subadmin" <?= $user->getRole() === 'subadmin' ? 'selected' : '' ?>>Subadmin</option>
                                            <option value="user" <?= $user->getRole() === 'user' ? 'selected' : '' ?>>User</option>
                                        </select>
                                        <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                        <button type="submit" class="btn btn-success ml-2">Update</button>
                                    </form>
                                <?php else: ?>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user->getRole()) ?>" readonly>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>Address</label>
                                <textarea class="form-control" rows="4" readonly><?= htmlspecialchars($info ? ($info->getAddress() ?? '') : '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="pt-3 pb-5 text-center">
                <form action="?page=user_status" method="POST">
                    <input type="hidden" name="id" value="<?= $user->getId() ?>">
                    <?php if ($user->getStatus() === 'active'): ?>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to block this user?')">Block User</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to unblock this user?')">Unblock User</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
</div>
