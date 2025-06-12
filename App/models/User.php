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
    private string $role;
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

    

    public function create(User $user): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, phone, email, password, gender, date_of_birth)
            VALUES (:name, :phone, :email, :password, :gender, :dob)
        ");

        return $stmt->execute([
            ':name' => $user->getName(),
            ':phone' => $user->getPhone(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':gender' => $user->getGender(),
            ':dob' => $user->getDateOfBirth()
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
        $user = $this->getById($userId);
        if (!$user) return false;

        $unchanged = true;
        foreach ($data as $key => $value) {
            $getter = 'get' . ucfirst($key);
            if (method_exists($user, $getter) && $user->$getter() != $value) {
                $unchanged = false;
                break;
            }
        }

        if ($unchanged) return false;

        $stmt = $this->db->prepare("
            UPDATE users
            SET name = :name, email = :email, phone = :phone, gender = :gender, date_of_birth = :date_of_birth
            WHERE id = :id
        ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':gender' => $data['gender'],
            ':date_of_birth' => $data['date_of_birth'],
            ':id' => $userId
        ]);
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
        return $user;
    }
}