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
        return ['error' => 'يوم مطلوب'];
    }

    // تحويل اليوم لأحرف صغيرة عشان نتجنب مشاكل التنسيق
    $dayOfWeek = (strlen($day) > 7) ? date('l', strtotime($day)) : $day;
    $dayOfWeek = strtolower($dayOfWeek); // حروف صغيرة
    error_log("جاري جلب الساعات لـ doctor_id={$this->doctor_id}, day=$dayOfWeek");

    $query = "
        SELECT start_time, end_time
        FROM doctor_schedule
        WHERE doctor_id = :doctor_id
        AND LOWER(day_of_week) = :day
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute([
        'doctor_id' => $this->doctor_id,
        'day' => $dayOfWeek,
    ]);
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$schedules) {
        error_log("لا توجد مواعيد لـ doctor_id={$this->doctor_id}, day=$dayOfWeek");
        return ['error' => 'لا توجد مواعيد متاحة لهذا اليوم'];
    }

    $availableHours = [];
    $today = new DateTime();
    $targetDate = new DateTime();

    // نبحث عن اليوم المناسب
    for ($i = 0; $i < 7; $i++) {
        if (strtolower($targetDate->format('l')) === $dayOfWeek) {
            break;
        }
        $targetDate->modify('+1 day');
    }

    // لو التاريخ في الماضي، نضيف أسبوع
    if ($targetDate < $today) {
        $targetDate->modify('+7 days');
    }
    $bookingDate = $targetDate->format('Y-m-d');
    error_log("تاريخ الحجز: $bookingDate");

    foreach ($schedules as $schedule) {
        $start = new DateTime($schedule['start_time']);
        $end = new DateTime($schedule['end_time']);
        $interval = new DateInterval('PT30M'); // كل 30 دقيقة
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
    return $availableHours;
}
}