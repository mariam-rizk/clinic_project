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
}

