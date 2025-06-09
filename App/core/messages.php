<?php
use App\core\Session;

$errors = Session::flash('errors') ?? [];
$old = Session::flash('old') ?? [];
$success = Session::flash('success') ?? null;
$info = Session::flash('info') ?? null;


if ($success): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
<?php endif; 

if ($info): ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($info) ?></div>
<?php endif; 


if (isset($errors[0]) && is_string($errors[0])): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($errors[0]) ?></div>
<?php endif; ?>




