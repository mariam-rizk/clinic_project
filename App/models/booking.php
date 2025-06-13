<?php
namespace App\models;

use PDO;
use PDOException;
use DateTime;
use DateInterval;
use DatePeriod;


class Booking {
    private $db;
    private $user_id;
    private $doctor_id;

    public function __construct(PDO $db, $user_id, $doctor_id) {
        $this->db = $db;
        $this->user_id = $user_id;
        $this->doctor_id = $doctor_id;
    }

    public function isTimeAvailable($day, $hour) {
        $query = "
            SELECT id
            FROM doctor_schedule
            WHERE doctor_id = :doctor_id
            AND day_of_week = :day
            AND :hour BETWEEN start_time AND end_time
            AND NOT EXISTS (
                SELECT 1 FROM bookings
                WHERE doctor_id = :doctor_id
                AND booking_date = :booking_date
                AND booking_time = :hour
                AND status = 'confirmed'
            )
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'doctor_id' => $this->doctor_id,
            'day' => $day,
            'hour' => $hour,
            'booking_date' => date('Y-m-d', strtotime($day)),
        ]);
        return $stmt->fetch() !== false;
    }

    public function setData($data) {
        if (empty($data['day']) || empty($data['hour']) || empty($data['location'])) {
            throw new \Exception('Missing required fields');
        }

        if (!$this->isTimeAvailable($data['day'], $data['hour'])) {
            throw new \Exception('Selected time is not available');
        }

        $query = "
            INSERT INTO bookings (user_id, doctor_id, booking_date, booking_time, status, location)
            VALUES (:user_id, :doctor_id, :booking_date, :booking_time, 'pending', :location)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $this->user_id,
            'doctor_id' => $this->doctor_id,
            'booking_date' => date('Y-m-d', strtotime($data['day'])),
            'booking_time' => $data['hour'],
            'location' => $data['location'],
        ]);

        return true;
    }

    public function confirmBooking($booking_id) {
        $query = "
            UPDATE bookings
            SET status = 'confirmed'
            WHERE id = :booking_id
            AND user_id = :user_id
            AND doctor_id = :doctor_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'booking_id' => $booking_id,
            'user_id' => $this->user_id,
            'doctor_id' => $this->doctor_id,
        ]);

        if ($stmt->rowCount() === 0) {
            throw new \Exception('Booking not found or cannot be confirmed');
        }

        $query = "
            SELECT booking_date, booking_time
            FROM bookings
            WHERE id = :booking_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['booking_id' => $booking_id]);
        $booking = $stmt->fetch();

        if ($booking) {
            $query = "
                DELETE FROM doctor_schedule
                WHERE doctor_id = :doctor_id
                AND day_of_week = :day_of_week
                AND start_time = :booking_time
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'doctor_id' => $this->doctor_id,
                'day_of_week' => date('l', strtotime($booking['booking_date'])),
                'booking_time' => $booking['booking_time'],
            ]);
        }

        return true;
    }
}