<?php
use App\models\Schedule;
$schedule_id = $_POST['schedule_id'];
$schedule = Schedule::getById($db,$schedule_id);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Appointment</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="users.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form
            action="dashboard.php?page=schedule-controller&action=edit-schedule"
            method="POST" enctype="multipart/form-data">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" id="doctor_id" name="doctor_id" value="<?=$schedule->getScheduleDoctorId()?>">
                                    <input type="hidden" class="form-control" id="schedule_id" name="schedule_id" value="<?=$schedule_id?>">
                                    
                                    <label for="day">Day</label>
                                    <select name="day" class="form-control" aria-label="Default select example">
                                        <option disabled <?= $schedule->getDay() ? '' : 'selected' ?>>Select Day
                                        </option>
                                        <option value="saturday"
                                            <?= $schedule->getDay() == 'saturday' ? 'selected' : '' ?>>Saturday</option>
                                        <option value="sunday" <?= $schedule->getDay() == 'sunday' ? 'selected' : '' ?>>
                                            Sunday</option>
                                        <option value="monday" <?= $schedule->getDay() == 'monday' ? 'selected' : '' ?>>
                                            Monday</option>
                                        <option value="tuesday"
                                            <?= $schedule->getDay() == 'tuesday' ? 'selected' : '' ?>>Tuesday</option>
                                        <option value="wednesday"
                                            <?= $schedule->getDay() == 'wednesday' ? 'selected' : '' ?>>Wednesday
                                        </option>
                                        <option value="thursday"
                                            <?= $schedule->getDay() == 'thursday' ? 'selected' : '' ?>>Thursday</option>
                                        <option value="friday" <?= $schedule->getDay() == 'friday' ? 'selected' : '' ?>>
                                            Friday</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start-time">Start Time</label>
                                    <input type="time" class="form-control" id="startTime" name="start_time"
                                        value="<?= $schedule->getStartTime() ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end-time">End Time</label>
                                    <input type="time" class="form-control" id="endTime" name="end_time"
                                        value="<?= $schedule->getEndTime() ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-warning">Edit</button>
                    <a href="users.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>