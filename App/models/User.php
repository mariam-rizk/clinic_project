<?php

namespace App\models;

use PDO;

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, phone, email, password, gender, date_of_birth)
            VALUES (:name, :phone, :email, :password, :gender, :date_of_birth)
        ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':gender' => $data['gender'],
            ':date_of_birth' => $data['dateofbirth']
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getById(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateUserInfo(int $userId, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET name = :name, email = :email, phone = :phone, gender = :gender, date_of_birth = :date_of_birth
            WHERE id = :user_id
        ");
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':gender' => $data['gender'],
            ':date_of_birth' => $data['date_of_birth'],
            ':user_id' => $userId
        ]);
    }

    
}

  



