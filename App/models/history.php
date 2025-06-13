<?php
namespace App\models;

use PDO;
use PDOException;
use DateTime;
use DateInterval;
use DatePeriod;

class History {
    private $db; 
    private $user_id; 

    public function __construct(PDO $db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    public function getPatientHistory() {
    try {
        $query = "
            SELECT 
                b.id AS booking_id,
                b.booking_date AS date,
                d.name AS doctor_name,
                d.id AS doctor_id,
                m.name AS major,
                b.location,
                b.status AS completed
            FROM bookings b
            LEFT JOIN doctors d ON b.doctor_id = d.id
            LEFT JOIN majors m ON d.major_id = m.id
            WHERE b.user_id = :user_id
            ORDER BY b.booking_date DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $this->user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Patient History Result: " . print_r($result, true));
        return $result;
    } catch (\Exception $e) {
        error_log("Error in getPatientHistory: " . $e->getMessage());
        return [];
    }
}
}
