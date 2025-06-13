<?php
use App\models\User;
use App\models\Doctor;

$userModel = new User($db);
$userCount = $userModel->countUsers();

$doctorCount = Doctor::countDoctors($db);
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
                <div class="col-sm-6">
                    <!-- Reserved for future controls -->
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Total Users -->
                <div class="col-lg-3 col-6">
                    <div class="small-box card">
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
                <div class="col-lg-3 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3><?= $doctorCount ?></h3>
                            <p>Total Doctors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-lg-3 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="col-lg-3 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>50</h3>
                            <p>Total Customers</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-stalker"></i>
                        </div>
                        <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Total Sale -->
                <div class="col-lg-4 col-6 mt-4">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>$1000</h3>
                            <p>Total Sale</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-cash"></i>
                        </div>
                        <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

