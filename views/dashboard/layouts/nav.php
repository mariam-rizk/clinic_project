<?php

$user = $_SESSION['admin_user'] ?? null;
$role = $user['role'] ?? '';
$name = $role === 'doctor' ? 'Dr. ' . htmlspecialchars($user['name']) : htmlspecialchars($user['name']);
$email = $user['email'] ?? '';
$photo = !empty($user['photo']) 
    ? 'uploads/profile_pictures/' . $user['photo'] 
    : "https://cdn-icons-png.flaticon.com/512/847/847969.png";
    
?>


<!-- nav -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
        <img src="<?= htmlspecialchars($photo) ?>" class="img-circle elevation-2" width="40" height="40" alt="User Image">
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">
        <h4 class="h4 mb-0"><strong><?= $name ?></strong></h4>
        <div class="mb-3"><?= htmlspecialchars($email) ?></div>
        <div class="dropdown-divider"></div>
        <?php if ($role === 'doctor'): ?>
          <a href="?page=doctor_settings" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> Settings
          </a>
        <?php elseif ($role === 'admin' || $role === 'subadmin'): ?>
          <a href="?page=admin_settings" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> Settings
          </a>
        <?php endif; ?>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-lock mr-2"></i> Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="?page=admin_logout" onclick="return confirm('Are you sure you want to logout?')" class="dropdown-item text-danger">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
<?php

