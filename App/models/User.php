<?php

namespace App\models;

use PDO;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $phone;
    private string $password;
    private string $gender;
    private string $dateOfBirth;
    private string $role = 'user'; 
    private string $status = 'active'; 
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getPhone(): string { return $this->phone; }
    public function setPhone(string $phone): void { $this->phone = $phone; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getGender(): string { return $this->gender; }
    public function setGender(string $gender): void { $this->gender = $gender; }

    public function getDateOfBirth(): string { return $this->dateOfBirth; }
    public function setDateOfBirth(string $dob): void { $this->dateOfBirth = $dob; }

    public function getRole(): string { return $this->role; }
    public function setRole(string $role): void { $this->role = $role; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    

    public function create(): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, phone, email, password, gender, date_of_birth, role, status)
            VALUES (:name, :phone, :email, :password, :gender, :dob, :role, :status)
        ");
        
        return $stmt->execute([
            ':name' => $this->getName(),
            ':phone' => $this->getPhone(),
            ':email' => $this->getEmail(),
            ':password' => $this->getPassword(),
            ':gender' => $this->getGender(),
            ':dob' => $this->getDateOfBirth(),
            ':role' => $this->getRole(),
            ':status' => $this->getStatus(),
        ]);
    }


    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return $this->userRow($row);
    }

    public function getById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return $this->userRow($row);
    }

    public function updateUserInfo(int $userId, array $data): bool
    {
        $allowedFields = ['name', 'email', 'phone', 'gender', 'date_of_birth', 'role'];
        $fields = [];
        $params = [':id' => $userId];
    
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
    
        if (empty($fields)) return false;
    
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

  
    private function userRow(array $row): User
    {
        $user = new User($this->db);
        $user->setId($row['id']);
        $user->setName($row['name']);
        $user->setEmail($row['email']);
        $user->setPhone($row['phone']);
        $user->setPassword($row['password']);
        $user->setGender($row['gender']);
        $user->setDateOfBirth($row['date_of_birth']);
        $user->setRole($row['role']);
        $user->setStatus($row['status']);
        return $user;
    }
    
    public function getAll(): array
	{
		$stmt = $this->db->prepare("SELECT * FROM users");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users
            WHERE name LIKE :keyword 
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
    
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT $limit OFFSET $offset");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function paginationCount(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return (int) $stmt->fetchColumn();
    }


    public function countUsers(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return (int) $stmt->fetchColumn();
    }

    
}