<?php
use App\models\User;

use App\models\Booking;

$userModel = new User($db);
$userCount = $userModel->countUsers();



$doctorId = $_SESSION['doctor']['id'] ?? null;


$booking = new Booking($db, null, $doctorId);
 
$totalBookings = $booking->countAllBookings(); 
$pending = $booking->countPendingBookings();

    
?>



?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Total Users -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="small-box card h-100 d-flex flex-column justify-content-between">
                        <div class="inner">
                            <h3><?= $userCount ?></h3>
                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="?page=manage_users" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Total Doctors -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="small-box card h-100 d-flex flex-column justify-content-between">
                        <div class="inner">
                            <h3><?= $doctorCount ?></h3>
                            <p>Total Doctors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="?page=manage_doctors" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            <!-- Total Bookings -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="small-box card h-100 d-flex flex-column justify-content-between">
                    <div class="inner">
                        <h3><?= $totalBookings ?></h3>
                        <p>Total Bookings</p>
                        <p class="text-muted mb-0">Pending: <?= $pending ?></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-calendar"></i>
                    </div>
                    <a href="?page=manage_bookings" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            </div>
        </div>
    </section>
</div>

