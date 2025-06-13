<?php
namespace App\controllers\dashboardControllers;
use PDO;

class BookingsController
{
    private $pdo;
    private $itemsPerPage = 5;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        try {
            $page = isset($_GET['page_num']) && is_numeric($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
            $offset = ($page - 1) * $this->itemsPerPage;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';

            $countQuery = "SELECT COUNT(*) FROM bookings b
                           JOIN users u ON b.user_id = u.id
                           JOIN doctors d ON b.doctor_id = d.id
                           JOIN majors m ON d.major_id = m.id";
            if ($search) {
                $countQuery .= " WHERE b.status LIKE :search";
            }
            $countStmt = $this->pdo->prepare($countQuery);
            if ($search) {
                $countStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            }
            $countStmt->execute();
            $totalItems = $countStmt->fetchColumn();
            $totalPages = ceil($totalItems / $this->itemsPerPage);

            $query = "SELECT 
                        b.id,
                        b.user_id,
                        u.name AS user_name,
                        u.phone AS user_phone,
                        b.doctor_id,
                        d.name AS doctor_name,
                        m.name AS major_name,
                        b.booking_date,
                        b.booking_time,
                        b.status,
                        b.created_at,
                        b.location
                      FROM bookings b
                      JOIN users u ON b.user_id = u.id
                      JOIN doctors d ON b.doctor_id = d.id
                      JOIN majors m ON d.major_id = m.id";
            if ($search) {
                $query .= " WHERE b.status LIKE :search";
            }
            $query .= " LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($query);
            if ($search) {
                $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', $this->itemsPerPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['bookings_data'] = $bookings;
            $_SESSION['pagination'] = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems
            ];
            $_SESSION['search'] = $search;

            error_log("Number of bookings: " . count($bookings));
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while fetching bookings data: " . $e->getMessage()];
        }
    }

    public function edit()
    {
        try {
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                throw new \Exception("Invalid booking ID.");
            }

            $query = "SELECT b.id, b.user_id, u.name AS user_name, u.phone AS user_phone, 
                             b.doctor_id, d.name AS doctor_name, m.name AS major_name, 
                             b.booking_date, b.booking_time, b.status,  b.location
                      FROM bookings b
                      JOIN users u ON b.user_id = u.id
                      JOIN doctors d ON b.doctor_id = d.id
                      JOIN majors m ON d.major_id = m.id
                      WHERE b.id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$booking) {
                throw new \Exception("Booking not found.");
            }

            $_SESSION['booking_data'] = $booking;
        } catch (\Exception $e) {
            error_log("Error while fetching booking data: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while fetching booking data: " . $e->getMessage()];
            $_SESSION['booking_data'] = null;
        }
    }

    public function update()
    {
        try {
            $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
            $status = trim($_POST['status'] ?? '');

            if ($id <= 0) {
                throw new \Exception("Invalid booking ID.");
            }

            if (!in_array($status, ['pending', 'confirmed', 'cancelled'])) {
                throw new \Exception("Invalid booking status.");
            }

            $query = "UPDATE bookings SET status = :status WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['success'] = "Booking status updated successfully.";
            header("Location: ?page=manage_bookings");
            exit;
        } catch (\Exception $e) {
            error_log("Error while updating booking: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while updating booking: " . $e->getMessage()];
            header("Location: ?page=edit_booking&id=$id");
            exit;
        }
    }

    public function delete()
    {
        try {
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
            error_log("Delete request received with ID: " . (isset($_GET['id']) ? $_GET['id'] : 'unset'));
            if ($id <= 0) {
                throw new \Exception("Invalid booking ID.");
            }

            // Check if booking exists
            $query = "SELECT id FROM bookings WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if (!$stmt->fetch()) {
                throw new \Exception("Booking not found.");
            }

            // Perform deletion
            $query = "DELETE FROM bookings WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success'] = "Booking deleted successfully.";
            header("Location: ?page=manage_bookings");
            exit;
        } catch (\Exception $e) {
            error_log("Error while deleting booking: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while deleting booking: " . $e->getMessage()];
            header("Location: ?page=manage_bookings");
            exit;
        }
    }
}