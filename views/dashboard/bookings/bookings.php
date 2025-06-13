<?php
use App\core\Session;

// Fetch bookings data and search term
$bookings = isset($_SESSION['bookings_data']) && is_array($_SESSION['bookings_data']) ? $_SESSION['bookings_data'] : [];
$search = isset($_SESSION['search']) ? htmlspecialchars($_SESSION['search']) : '';

$messagesPath = __DIR__ . '/../../../App/core/messages.php';
if (!file_exists($messagesPath)) {
    echo '<div class="alert alert-danger">Error: messages.php file not found.</div>';
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bookings</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Display flashed messages -->
            <?php if (file_exists($messagesPath)) {
                include $messagesPath;
            } ?>
            <!-- Table Section -->
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <form action="?page=manage_bookings" method="GET" class="input-group" style="width: 250px;">
                            <input type="hidden" name="page" value="manage_bookings">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search by status (pending, confirmed, cancelled)" value="<?= $search ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table id="bookings_table" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Doctor</th>
                                <th>Booking Date</th>
                                <th>Booking Time</th>
                                <th>Status</th>
                                
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bookings)): ?>
                                <?php foreach($bookings as $booking): ?>
                                    <?php if (isset($booking['id']) && is_numeric($booking['id']) && $booking['id'] > 0): ?>
                                        <tr>
                                            <td><?= $booking['id'] ?></td>
                                            <td><?= htmlspecialchars($booking['user_name']) . ' (' . htmlspecialchars($booking['user_phone']) . ')' ?></td>
                                            <td><?= htmlspecialchars($booking['doctor_name']) . ' (' . htmlspecialchars($booking['major_name']) . ')' ?></td>
                                            <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                                            <td><?= htmlspecialchars($booking['booking_time']) ?></td>
                                            <td><?= htmlspecialchars($booking['status']) ?></td>
                                            <td><?= htmlspecialchars($booking['created_at']) ?></td>
                                            <td>
                                                <a href="?page=edit_booking&id=<?= $booking['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                                <a href="?page=delete_booking&id=<?= $booking['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete booking ID: <?= $booking['id'] ?>?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">Error: Invalid booking ID (<?= isset($booking['id']) ? $booking['id'] : 'not found' ?>)</td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No bookings available currently</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <?php if (isset($_SESSION['pagination']) && $_SESSION['pagination']['total_pages'] > 1): ?>
                        <ul class="pagination pagination m-0 float-right">
                            <li class="page-item <?= $_SESSION['pagination']['current_page'] == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=manage_bookings&page_num=<?= $_SESSION['pagination']['current_page'] - 1 ?>&search=<?= urlencode($search) ?>">«</a>
                            </li>
                            <?php for ($i = 1; $i <= $_SESSION['pagination']['total_pages']; $i++): ?>
                                <li class="page-item <?= $_SESSION['pagination']['current_page'] == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=manage_bookings&page_num=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $_SESSION['pagination']['current_page'] == $_SESSION['pagination']['total_pages'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=manage_bookings&page_num=<?= $_SESSION['pagination']['current_page'] + 1 ?>&search=<?= urlencode($search) ?>">»</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>