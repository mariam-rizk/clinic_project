<?php
use App\core\Session;

$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];


unset($_SESSION['success']);
unset($_SESSION['errors']);


$major = isset($_SESSION['major_data']) ? $_SESSION['major_data'] : null;
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Major</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="?page=manage_majors" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">

        <div class="container-fluid">
            <?php if ($success): ?>
                <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($major): ?>
                <div class="card">
                    <div class="card-body">
                        <form action="?page=update_major" method="POST">
                            <input type="hidden" name="id" value="<?= $major['id'] ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Major Name</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Major Name" value="<?= htmlspecialchars($major['name']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" placeholder="Description" rows="4"><?= htmlspecialchars($major['description']) ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-5 pt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="?page=manage_majors" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">Major not found.</div>
            <?php endif; ?>

        </div>
    </section>

</div>