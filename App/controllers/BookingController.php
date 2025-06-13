<?php
namespace App\controllers;

use PDO;
use App\core\Session;
use App\Models\Booking;
use App\Models\DoctorSchedule;

class BookingController
{
    private $db;
    private $userId;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userId = Session::get('user')['id'] ?? null;
    }

    public function create($doctorId)
    {
        if (!$this->userId) {
            Session::set('error', 'Please login to create a booking');
            header("Location: ?page=login");
            exit;
        }

        if (!is_numeric($doctorId) || $doctorId <= 0) {
            Session::set('error', 'Invalid doctor ID');
            header("Location: ?page=doctors");
            exit;
        }

        try {
            $query = "SELECT name, email FROM users WHERE id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $this->userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                Session::set('error', 'User not found');
                header("Location: ?page=home");
                exit;
            }
        } catch (\PDOException $e) {
            Session::set('error', 'Failed to fetch user: ' . $e->getMessage());
            header("Location: ?page=home");
            exit;
        }

        try {
            $query = "
                SELECT d.name, d.image, m.name AS major_id, d.bio
                FROM doctors d
                LEFT JOIN majors m ON d.major_id = m.id
                WHERE d.id = :doctor_id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['doctor_id' => $doctorId]);
            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$doctor) {
                Session::set('error', 'Doctor not found');
                header("Location: ?page=doctors");
                exit;
            }
            $doctor['image'] = $doctor['image'] ?? '/path/to/default/image.jpg';
        } catch (\PDOException $e) {
            Session::set('error', 'Failed to fetch doctor: ' . $e->getMessage());
            header("Location: ?page=doctors");
            exit;
        }

        $doctorSchedule = new DoctorSchedule($this->db, $doctorId);
        $availableDays = $doctorSchedule->getAvailableDays();
        $doctor_id = $doctorId;

        $selectedDay = $_GET['day'] ?? (!empty($availableDays) ? $availableDays[0] : '');
        $availableHours = [];
        if ($selectedDay) {
            $availableHours = $doctorSchedule->getAvailableHours($selectedDay);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingModel = new Booking($this->db, $this->userId, $doctorId);
            try {
                $bookingModel->setData([
                    'day' => $_POST['day'] ?? '',
                    'hour' => $_POST['hour'] ?? '',
                    'location' => $_POST['location'] ?? 'Main Clinic'
                ]);
                Session::set('success', 'Booking created successfully');
                header("Location: ?page=history");
                exit;
            } catch (\Exception $e) {
                Session::set('error', $e->getMessage());
            }
        }

        require_once __DIR__ . '/../../views/bookings/booking_form.php';
    }

    public function confirm($bookingId)
    {
        if (!$this->userId) {
            Session::set('error', 'Please login to confirm a booking');
            header("Location: ?page=login");
            exit;
        }

        $bookingModel = new Booking($this->db, $this->userId, null);
        try {
            $bookingModel->confirmBooking($bookingId);
            Session::set('success', 'Booking confirmed successfully');
        } catch (\Exception $e) {
            Session::set('error', $e->getMessage());
        }
        header("Location: ?page=history");
        exit;
    }

    public function getAvailableHours($doctorId)
    {
        header('Content-Type: application/json');

        $day = $_GET['day'] ?? '';
        if (empty($day) || !is_string($day)) {
            http_response_code(400);
            echo json_encode(['error' => 'Day is not specified or invalid']);
            error_log("Error: Day is empty or invalid: $day");
            exit;
        }

        if (!is_numeric($doctorId) || $doctorId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid doctor ID']);
            error_log("Error: Invalid doctor ID: $doctorId");
            exit;
        }

        try {
            $doctorSchedule = new DoctorSchedule($this->db, $doctorId);
            $hours = $doctorSchedule->getAvailableHours($day);
            
            if (isset($hours['error'])) {
                http_response_code(400);
                echo json_encode(['error' => $hours['error']]);
                error_log("Error fetching hours: {$hours['error']}");
                exit;
            }
            
            echo json_encode(['hours' => $hours]);
            error_log("Returned hours: " . json_encode($hours));
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
            error_log("Server error: " . $e->getMessage());
        }
        exit;
    }
}