<?php

namespace App\models;

use PDO;
use PDOException;

class Doctor
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $phone;
    private $gender;
    private $image;
    private $major_id;
    private $bio;

    public function __construct($id, $name, $email, $password, $phone, $gender, $image, $major_id, $bio)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->gender = $gender;
        $this->image = $image;
        $this->major_id = $major_id;
        $this->bio = $bio;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getPhone() { return $this->phone; }
    public function getGender() { return $this->gender; }
    public function getImage() { return $this->image; }
    public function getBio() { return $this->bio; }

    public function getMajorId($pdo)
    {
        try {
            $stm = $pdo->prepare("SELECT name FROM majors WHERE id= :id");
            $stm->bindParam(":id", $this->major_id);
            $stm->execute();
            $major = $stm->fetch(PDO::FETCH_ASSOC);
            return $major['name'] ?? null;
        } catch (PDOException $e) {
            echo "Error fetching major name: " . $e->getMessage();
            return null;
        }
    }

    public static function createDoctor(PDO $pdo, $name, $email, $password, $phone, $gender, $image, $major_id, $bio)
    {
        $stm = $pdo->prepare("INSERT INTO doctors (`name`, `email`, `phone`, `gender`, `image`, `major_id`, `bio`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stm->bindParam(1, $name);
        $stm->bindParam(2, $email);
        $stm->bindParam(3, $phone);
        $stm->bindParam(4, $gender);
        $stm->bindParam(5, $image);
        $stm->bindParam(6, $major_id);
        $stm->bindParam(7, $bio);
        $stm->bindParam(8, $password);

        if ($stm->execute()) {
            $id = $pdo->lastInsertId();
            return new Doctor($id, $name, $email, $password, $phone, $gender, $image, $major_id, $bio);
        }

        return null;
    }

    public static function deleteDoctor($pdo, int $id)
    {
        try {
            $stm = $pdo->prepare("DELETE FROM doctors WHERE id = :id");
            $stm->bindParam(":id", $id);
            $stm->execute();
            return $stm->rowCount() > 0;
        } catch (PDOException $e) {
            return "Error deleting doctor: " . $e->getMessage();
        }
    }

    public static function editDoctor($pdo, $id, $name, $email, $phone, $gender, $image, $major_id, $bio)
    {
        try {
            $stm = $pdo->prepare("UPDATE doctors SET `name` = :name, `email` = :email, `phone` = :phone, `gender` = :gender, `image` = :image, `major_id` = :major_id, `bio` = :bio WHERE `id` = :id");
            $stm->bindParam(':name', $name);
            $stm->bindParam(':email', $email);
            $stm->bindParam(':phone', $phone);
            $stm->bindParam(':gender', $gender);
            $stm->bindParam(':image', $image);
            $stm->bindParam(':major_id', $major_id);
            $stm->bindParam(':bio', $bio);
            $stm->bindParam(':id', $id);
            $stm->execute();
            return $stm->rowCount() > 0;
        } catch (PDOException $e) {
            return "Error updating doctor: " . $e->getMessage();
        }
    }

    public static function getAll(PDO $pdo)
    {
        $stm = $pdo->prepare("SELECT * FROM `doctors`");
        $stm->execute();
        $results = $stm->fetchAll(PDO::FETCH_ASSOC);
        $doctors = [];
        foreach ($results as $doctor) {
            $doctors[] = new Doctor($doctor['id'], $doctor['name'], $doctor['email'], $doctor['password'], $doctor['phone'], $doctor['gender'], $doctor['image'], $doctor['major_id'], $doctor['bio']);
        }
        return $doctors;
    }

    public static function getByMajor(PDO $pdo, $major)
    {
        $stm = $pdo->prepare("SELECT * FROM doctors WHERE major_id = (SELECT id FROM majors WHERE name= :major)");
        $stm->bindParam(":major", $major);
        $stm->execute();
        $results = $stm->fetchAll(PDO::FETCH_ASSOC);
        $doctors = [];
        foreach ($results as $doctor) {
            $doctors[] = new Doctor($doctor['id'], $doctor['name'], $doctor['email'], $doctor['password'], $doctor['phone'], $doctor['gender'], $doctor['image'], $doctor['major_id'], $doctor['bio']);
        }
        return $doctors;
    }

    public static function getById(PDO $pdo, $id)
    {
        $stm = $pdo->prepare("SELECT * FROM doctors WHERE id = :id");
        $stm->bindParam(":id", $id);
        $stm->execute();
        $doctor = $stm->fetch(PDO::FETCH_ASSOC);
        if ($doctor) {
            return new Doctor($doctor['id'], $doctor['name'], $doctor['email'], $doctor['password'], $doctor['phone'], $doctor['gender'], $doctor['image'], $doctor['major_id'], $doctor['bio']);
        }
        return null;
    }

    public static function countDoctors(PDO $pdo)
    {
        $stm = $pdo->prepare("SELECT count(*) as count FROM `doctors`");
        $stm->execute();
        $count = $stm->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    public static function findByEmail(PDO $pdo, string $email): ?Doctor
    {
        $stmt = $pdo->prepare("SELECT * FROM doctors WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor) {
            return new Doctor($doctor['id'], $doctor['name'], $doctor['email'], $doctor['password'], $doctor['phone'], $doctor['gender'], $doctor['image'], $doctor['major_id'], $doctor['bio']);
        }

        return null;
    }
}
