<?php
use App\core\Session;

$messagesPath = __DIR__ . '/../../../App/core/messages.php';
if (!file_exists($messagesPath)) {
    echo '<div class="alert alert-danger">Error: messages.php file not found.</div>';
}

// Get booking data from session (set by BookingsController::edit)
$booking = isset($_SESSION['booking_data']) ? $_SESSION['booking_data'] : null;
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Booking</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="?page=manage_bookings" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <?php if (file_exists($messagesPath)) {
                include $messagesPath;
            } ?>
            <?php if ($booking): ?>
                <div class="card">
                    <div class="card-body">
                        <form action="?page=update_booking" method="POST">
                            <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user">User</label>
                                        <input type="text" id="user" class="form-control" value="<?= htmlspecialchars($booking['user_name'] . ' (' . $booking['user_phone'] . ')') ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="doctor">Doctor</label>
                                        <input type="text" id="doctor" class="form-control" value="<?= htmlspecialchars($booking['doctor_name'] . ' (' . $booking['major_name'] . ')') ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="booking_date">Booking Date</label>
                                        <input type="text" id="booking_date" class="form-control" value="<?= htmlspecialchars($booking['booking_date']) ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="booking_time">Booking Time</label>
                                        <input type="text" id="booking_time" class="form-control" value="<?= htmlspecialchars($booking['booking_time']) ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="pending" <?= $booking['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="confirmed" <?= $booking['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="cancelled" <?= $booking['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="location">Location</label>
                                        <input type="text" id="location" class="form-control" value="<?= htmlspecialchars($booking['location']) ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-5 pt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="?page=manage_bookings" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">Booking not found.</div>
            <?php endif; ?>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
</div>