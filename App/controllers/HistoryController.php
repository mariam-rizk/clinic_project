<?php
namespace App\controllers;

use PDO;
use App\core\Session;
use App\models\History;
use App\models\User;

class HistoryController
{
    private $db;
    private $userId;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userId = Session::get('user')['id'];
    }

    public function index()
    {
        if (!$this->userId) {
            error_log("No user ID in session");
            Session::set('error', 'Please login to view history');
            header("Location: ?page=login");
            exit;
        }

        // Check for user existence
        $userModel = new User($this->db);
        $user = $userModel->getById($this->userId);
        error_log("User Data: " . print_r($user, true));

        $historyModel = new History($this->db, $this->userId);
        $history = $historyModel->getPatientHistory();

        error_log("History Data: " . print_r($history, true));

        if (isset($history['error'])) {
            Session::set('error', $history['error']);
            header('Location: ?page=profile');
            exit;
        }

        // تعديل المسار ليشير لـ views/history.php
        require_once __DIR__ . '/../../views/bookings/history.php';
    }

    public function viewBooking($bookingId)
    {
        if (!$this->userId) {
            Session::set('error', 'Please login to view booking details');
            header("Location: ?page=login");
            exit;
        }

        $historyModel = new History($this->db, $this->userId);
        $history = $historyModel->getPatientHistory();
        
        if (isset($history['error'])) {
            Session::set('error', $history['error']);
            header('Location: ?page=history');
            exit;
        }

        $bookingDetails = null;
        foreach ($history as $booking) {
            if ($booking['booking_id'] == $bookingId) {
                $bookingDetails = $booking;
                break;
            }
        }

        if (!$bookingDetails) {
            Session::set('error', 'Booking not found or access denied');
            header('Location: ?page=history');
            exit;
        }

        // تعديل المسار هنا كمان
        require_once __DIR__ . '/../../views/bookings/history.php';
    }
}