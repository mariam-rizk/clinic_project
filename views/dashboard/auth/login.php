<?php
use App\core\Session;
$errors = Session::flash('errors') ?? [];
$old = Session::flash('old') ?? [];
$success = Session::flash('success');
$info = Session::flash('info');


$email = isset($old['email']) && !is_array($old['email']) ? htmlspecialchars($old['email']) : '';


Session::remove('old');
?>

<?php if (!empty($errors[0])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errors[0]) ?></div>
<?php endif; ?>

<?php if ($success && is_string($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if ($info && is_string($info)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($info) ?></div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Clinic :: Administrative Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="dashboard_assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dashboard_assets/css/adminlte.min.css">
    <link rel="stylesheet" href="dashboard_assets/css/custom.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h3">Administrative Panel</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form action="?page=login_controller" method="post">
                <div class="input-group mb-1">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?>">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <?php if (!empty($errors['email'])): ?>
                    <small class="text-danger d-block mb-2"><?= htmlspecialchars($errors['email'][0]) ?></small>
                <?php endif; ?>

                <div class="input-group mb-1">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <?php if (!empty($errors['password'])): ?>
                    <small class="text-danger d-block mb-2"><?= htmlspecialchars($errors['password'][0]) ?></small>
                <?php endif; ?>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember Me</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </div>
            </form>

            <p class="mb-1 mt-3">
                <a href="forgot-password.html">I forgot my password</a>
            </p>
        </div>
    </div>
</div>

<script src="dashboard_assets/plugins/jquery/jquery.min.js"></script>
<script src="dashboard_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dashboard_assets/js/adminlte.min.js"></script>
<script src="dashboard_assets/js/demo.js"></script>
</body>
</html>