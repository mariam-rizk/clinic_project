<?php
use App\core\Session;

$errors = Session::get('errors') ?? [];
$old = Session::get('old') ?? [];
$success = Session::get('success') ?? null;
$info = Session::get('info') ?? null;
?>

<?php if ($success): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if ($info): ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($info) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <?php if (is_string($errors)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($errors) ?></div>
    <?php elseif (is_array($errors) && array_keys($errors) === range(0, count($errors) - 1)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>


<?php
Session::remove('errors');
Session::remove('old');
Session::remove('success');
Session::remove('info');
?>




