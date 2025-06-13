<?php
namespace App\controllers\dashboardControllers;

use PDO;

class MajorsController
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

            $countQuery = "SELECT COUNT(*) FROM majors";
            if ($search) {
                $countQuery .= " WHERE name LIKE :search";
            }
            $countStmt = $this->pdo->prepare($countQuery);
            if ($search) {
                $countStmt->bindValue(':search', $search . '%', PDO::PARAM_STR);
            }
            $countStmt->execute();
            $totalItems = $countStmt->fetchColumn();
            $totalPages = ceil($totalItems / $this->itemsPerPage);

            $query = "SELECT 
                        majors.id, 
                        majors.name,
                        majors.description, 
                        (SELECT COUNT(*) FROM doctors WHERE doctors.major_id = majors.id) AS doctors_count
                      FROM majors";
            if ($search) {
                $query .= " WHERE name LIKE :search";
            }
            $query .= " LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($query);
            if ($search) {
                $stmt->bindValue(':search', $search . '%', PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', $this->itemsPerPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['majors_data'] = $majors;
            $_SESSION['pagination'] = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems
            ];
            $_SESSION['search'] = $search;

            error_log("Number of majors: " . count($majors));
            
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while fetching majors data: " . $e->getMessage()];
        }
    }

    public function create()
    {
        
    }

    public function store()
    {
        try {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                throw new \Exception("Major name is required.");
            }

            $query = "INSERT INTO majors (name, description) VALUES (:name, :description)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['success'] = "Major created successfully.";
            header("Location: ?page=manage_majors");
            exit;
        } catch (\Exception $e) {
            error_log("Error while creating major: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while creating the major: " . $e->getMessage()];
            header("Location: ?page=create_major");
            exit;
        }
    }

    public function edit()
    {
        try {
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                throw new \Exception("Invalid major ID.");
            }

            $query = "SELECT id, name, description FROM majors WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $major = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$major) {
                throw new \Exception("Major not found.");
            }

            $_SESSION['major_data'] = $major;
        } catch (\Exception $e) {
            error_log("Error while fetching major data: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while fetching major data: " . $e->getMessage()];
            $_SESSION['major_data'] = null;
        }
    }

    public function update()
    {
        try {
            $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($id <= 0) {
                throw new \Exception("Invalid major ID.");
            }

            if (empty($name)) {
                throw new \Exception("Major name is required.");
            }

            $query = "UPDATE majors SET name = :name, description = :description WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['success'] = "Major updated successfully.";
            header("Location: ?page=manage_majors");
            exit;
        } catch (\Exception $e) {
            error_log("Error while updating major: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while updating the major: " . $e->getMessage()];
            header("Location: ?page=edit_major&id=$id");
            exit;
        }
    }

    public function delete()
    {
        try {
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
            error_log("Delete request received with ID: " . (isset($_GET['id']) ? $_GET['id'] : 'unset'));
            if ($id <= 0) {
                throw new \Exception("Invalid major ID.");
            }


            $query = "SELECT id FROM majors WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if (!$stmt->fetch()) {
                throw new \Exception("Major not found.");
            }

            $query = "SELECT COUNT(*) FROM doctors WHERE major_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $doctorCount = $stmt->fetchColumn();

            if ($doctorCount > 0) {
                throw new \Exception("Cannot delete major because it is associated with doctors.");
            }

            $query = "DELETE FROM majors WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success'] = "Major deleted successfully.";
            header("Location: ?page=manage_majors");
            exit;
        } catch (\Exception $e) {
            error_log("Error while deleting major: " . $e->getMessage());
            $_SESSION['errors'] = ["An error occurred while deleting the major: " . $e->getMessage()];
            header("Location: ?page=manage_majors");
            exit;
        }
    }
}