<?php

use App\models\Schedule;

if ($_SERVER['REQUEST_METHOD'] = "POST") {
    $action = $_GET['action'];
    if ($action == 'add-schedule') {
        $doctor_id = trim($_GET['doctor-id']);
        $day = trim($_POST['day']);
        $start_time = trim($_POST['start_time']);
        $end_time = trim($_POST['end_time']);


        $newAppointment = Schedule::create($db, $doctor_id, $day, $start_time, $end_time);
        if ($newAppointment) {
            header("Location: dashboard.php?page=doctor-schedule&doctor-id={$doctor_id}");
            exit;
        }
    } elseif ($action == 'edit-schedule') {
        // print_r($_POST);
        try {
            $doctor_id = trim($_POST['doctor_id']);
            $schedule_id = trim($_POST['schedule_id']);
            $day = trim($_POST['day']);
            $start_time = trim($_POST['start_time']);
            $end_time = trim($_POST['end_time']);

            $update = Schedule::editSchedule($db, $schedule_id, $day, $start_time, $end_time);

            if ($update) {
                echo "<div class='alert alert-success'>Schedule updated successfully.</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to update schedule. No rows affected.</div>";
            }
            header("Location: dashboard.php?page=doctor-schedule&doctor-id={$doctor_id}");
            exit();
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    } elseif ($action == 'delete-schedule') {
        $doctor_id = trim($_GET['doctor-id']);
        $schedule_id = trim($_POST['schedule_id']);
        $del = Schedule::deleteSchedule($db, $schedule_id);
        // print_r($del);
        if ($del) {
            //  delete doctor successfully
        } else {
            //  error can not delete doctor   
        }
        header("Location: dashboard.php?page=doctor-schedule&doctor-id={$doctor_id}");
        exit();
    }
}
