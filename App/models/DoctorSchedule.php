<?php
namespace App\Models;

use PDO;
use DateTime;
use DateInterval;
use DatePeriod;

class DoctorSchedule {
    private $db;
    private $doctor_id;

    public function __construct(PDO $db, $doctor_id) {
        $this->db = $db;
        $this->doctor_id = $doctor_id;
    }

    public function getAvailableDays() {
        $query = "
            SELECT DISTINCT day_of_week
            FROM doctor_schedule
            WHERE doctor_id = :doctor_id
            ORDER BY FIELD(day_of_week, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['doctor_id' => $this->doctor_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAvailableHours($day) {
        if (empty($day)) {
            error_log("Error: Day is empty");
            return ['error' => 'Day is required'];
        }

        $dayOfWeek = (strlen($day) > 7) ? date('l', strtotime($day)) : $day;
        error_log("Fetching hours for doctor_id={$this->doctor_id}, day=$dayOfWeek");

        $query = "
            SELECT start_time, end_time
            FROM doctor_schedule
            WHERE doctor_id = :doctor_id
            AND day_of_week = :day
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'doctor_id' => $this->doctor_id,
            'day' => $dayOfWeek,
        ]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Schedules fetched: " . json_encode($schedules));

        if (!$schedules) {
            error_log("No schedules found for doctor_id={$this->doctor_id}, day=$dayOfWeek");
            return ['error' => 'No schedules available for this day'];
        }

        $availableHours = [];
        $today = new DateTime();
        $targetDate = clone $today;

        for ($i = 0; $i < 7; $i++) {
            if ($targetDate->format('l') === $dayOfWeek) {
                break;
            }
            $targetDate->modify('+1 day');
        }

        if ($targetDate < $today) {
            $targetDate->modify('+7 days');
        }
        $bookingDate = $targetDate->format('Y-m-d');
        error_log("Booking date: $bookingDate");

        foreach ($schedules as $schedule) {
            $start = new DateTime($schedule['start_time']);
            $end = new DateTime($schedule['end_time']);

            if ($end <= $start) {
                error_log("Error: Invalid time range for doctor_id={$this->doctor_id}, day=$dayOfWeek, start_time={$schedule['start_time']}, end_time={$schedule['end_time']}");
                return ['error' => 'Invalid time range: End time must be after start time'];
            }

            $interval = new DateInterval('PT30M');
            $period = new DatePeriod($start, $interval, $end);

            foreach ($period as $time) {
                $hour = $time->format('H:i');

                $query = "
                    SELECT 1
                    FROM bookings
                    WHERE doctor_id = :doctor_id
                    AND booking_date = :booking_date
                    AND booking_time = :hour
                    AND status = 'confirmed'
                ";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    'doctor_id' => $this->doctor_id,
                    'booking_date' => $bookingDate,
                    'hour' => $hour,
                ]);
                if ($stmt->fetch() === false) {
                    $availableHours[] = $hour;
                }
            }
        }

        $availableHours = array_unique($availableHours);
        sort($availableHours);
        error_log("Available hours: " . json_encode($availableHours));

        if (empty($availableHours)) {
            return ['error' => 'No hours available for this day'];
        }

        return $availableHours;
    }
}