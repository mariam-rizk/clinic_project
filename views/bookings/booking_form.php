<?php
use App\core\Session;
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
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($doctor['name'] ?? 'Booking Form'); ?></li>
            </ol>
        </nav>
        <div class="d-flex flex-column gap-3 details-card doctor-details">
            <div class="details d-flex gap-2 align-items-center">
                <img
                    src="<?php echo htmlspecialchars($doctor['image']); ?>"
                    alt="Doctor"
                    class="img-fluid rounded-circle"
                    height="150"
                    width="150"
                />
                <div class="details-info d-flex flex-column gap-3">
                    <h4 class="card-title fw-bold"><?php echo htmlspecialchars($doctor['name'] ); ?></h4>
                    <h6 class="card-title fw-bold"><?php echo htmlspecialchars(($doctor['major_id'] ) . ' - ' . ($doctor['bio'] )); ?></h6>
                </div>
            </div>
            <hr />
            <form class="form" method="POST" action="">
                <div class="form-items">
                    <div class="mb-3">
                        <label class="form-label required-label" for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label required-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label required-label" for="day">Day</label>
                        <select class="form-select" id="day" name="day" required>
                            <option value="" disabled selected>Select a day</option>
                            <?php if (!empty($availableDays)): ?>
                                <?php foreach ($availableDays as $day): ?>
                                    <option value="<?php echo htmlspecialchars($day); ?>">
                                        <?php echo htmlspecialchars($day); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No available days</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required-label" for="hour">Hour</label>
                        <select class="form-select" id="hour" name="hour" required>
                            <option value="" disabled selected>Select an hour</option>
                            <?php if (!empty($availableHours) && is_array($availableHours)): ?>
                                <?php foreach ($availableHours as $hour): ?>
                                    <option value="<?php echo htmlspecialchars($hour); ?>">
                                        <?php echo htmlspecialchars($hour); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No available hours</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required-label" for="location">Location</label>
                        <select class="form-select" id="location" name="location" required>
                            <option value="" disabled selected>Select a location</option>
                            <option value="Main Clinic">Main Clinic</option>
                            <option value="Downtown Clinic">Downtown Clinic</option>
                            <option value="West Clinic">West Clinic</option>
                            <option value="East Clinic">East Clinic</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
            </form>
        </div>
    </div>
</div>