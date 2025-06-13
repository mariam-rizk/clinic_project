<?php
use App\core\Session;

// Fetch messages from the session
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

// Clear messages from the session after fetching
unset($_SESSION['success']);
unset($_SESSION['errors']);

// Fetch majors data and search term
$majors = isset($_SESSION['majors_data']) && is_array($_SESSION['majors_data']) ? $_SESSION['majors_data'] : [];
$search = isset($_SESSION['search']) ? htmlspecialchars($_SESSION['search']) : '';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Majors</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="?page=create_major" class="btn btn-primary">New Major</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Display flashed messages -->
            <?php if ($success): ?>
                <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- Table Section -->
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <form action="?page=manage_majors" method="GET" class="input-group" style="width: 250px;">
                            <input type="hidden" name="page" value="manage_majors">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search" value="<?= $search ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table id="majors_table" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Major</th>
                                <th>Description</th>
                                <th>Doctors Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($majors)): ?>
                                <?php foreach($majors as $major): ?>
                                    <?php if (isset($major['id']) && is_numeric($major['id']) && $major['id'] > 0): ?>
                                        <tr>
                                            <td><?= $major['id'] ?></td>
                                            <td><?= htmlspecialchars($major['name']) ?></td>
                                            <td><?= htmlspecialchars($major['description']) ?></td>
                                            <td><?= $major['doctors_count'] ?></td>
                                            <td>
                                                <a href="?page=edit_major&id=<?= $major['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                                <a href="?page=delete_major&id=<?= $major['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the major: <?= htmlspecialchars($major['name']) ?>?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Error: Invalid major ID (<?= isset($major['id']) ? $major['id'] : 'not found' ?>)</td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No majors available currently</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <?php if (isset($_SESSION['pagination']) && $_SESSION['pagination']['total_pages'] > 1): ?>
                        <ul class="pagination pagination m-0 float-right">
                            <li class="page-item <?= $_SESSION['pagination']['current_page'] == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=manage_majors&page_num=<?= $_SESSION['pagination']['current_page'] - 1 ?>&search=<?= urlencode($search) ?>">«</a>
                            </li>
                            <?php for ($i = 1; $i <= $_SESSION['pagination']['total_pages']; $i++): ?>
                                <li class="page-item <?= $_SESSION['pagination']['current_page'] == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=manage_majors&page_num=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $_SESSION['pagination']['current_page'] == $_SESSION['pagination']['total_pages'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=manage_majors&page_num=<?= $_SESSION['pagination']['current_page'] + 1 ?>&search=<?= urlencode($search) ?>">»</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>