<?php
use App\core\Session;
use App\controllers\HistoryController;

error_log("Session Data in history.php: " . print_r(Session::get('user'), true));
if (!Session::has('user')) {
    header("Location: ?page=login");
    exit();
}
?>
<div class="page-wrapper">
    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="?page=home">Home</a></li>
                <li class="breadcrumb-item"><a class="text-decoration-none" href="?page=doctors">Doctors</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($doctor['name'] ?? 'Doctor'); ?></li>
            </ol>
        </nav>
        <div class="page-wrapper">
            <div class="container">
                <h1 class="text-center mb-4">Booking History</h1>
                <div class="table-responsive">
                    <table class="table table-striped history-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Doctor</th>
                                <th scope="col">Specialty</th>
                                <th scope="col">Location</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($history)): ?>
                                <?php $counter = 1; ?>
                                <?php foreach ($history as $record): ?>
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch ($record['completed']) {
                                        case 'confirmed':
                                            $statusClass = 'status-confirmed';
                                            $statusText = 'Confirmed';
                                            break;
                                        case 'pending':
                                            $statusClass = 'status-pending';
                                            $statusText = 'Pending';
                                            break;
                                        case 'completed':
                                            $statusClass = 'status-completed';
                                            $statusText = 'Completed';
                                            break;
                                        default:
                                            $statusClass = '';
                                            $statusText = ucfirst($record['completed']);
                                    }
                                    ?>
                                    <tr>
    <th scope="row"><?php echo $counter++; ?></th>
    <td><?php echo htmlspecialchars($record['date']); ?></td>
    <td class="d-flex align-items-center gap-2">
        <?php echo htmlspecialchars($record['doctor_name']); ?>
    </td>
    <td><?php echo htmlspecialchars($record['major'] ?? 'N/A'); ?></td>
    <td><?php echo htmlspecialchars($record['location']); ?></td>
    <td>
        <span class="status-badge <?php echo $statusClass; ?>">
            <?php echo $statusText; ?>
        </span>
    </td>
</tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times" style="font-size: 3rem; color: #6c757d; margin-bottom: 15px;"></i>
                                            <h3>No Booking History Found</h3>
                                            <p class="text-muted">You haven't made any bookings yet.</p>
                                            <a href="?page=doctors" class="btn btn-primary mt-3">
                                                Book an Appointment Now
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>