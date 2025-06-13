<?php
namespace App\models;

use PDO;

class Contact
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO contact (user_id, name, phone, email, subject, message)
            VALUES (:user_id, :name, :phone, :email, :subject, :message)"
        );

        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':subject' => $data['subject'],
            ':message' => $data['message'],
        ]);
    }


    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM contact ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM contact
            WHERE subject LIKE :keyword 
            ORDER BY id DESC
        ");
        $stmt->execute([
            ':keyword' => '%' . $keyword . '%',
        ]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pagination($limit, $offset): array
    {
        $limit = (int) $limit;
        $offset = (int) $offset;
    
        $stmt = $this->db->query("SELECT * FROM contact ORDER BY id DESC LIMIT $limit OFFSET $offset");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function paginationCount(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM contact");
        return (int) $stmt->fetchColumn();
    }

}

