<?php

namespace App\models;

use PDO;

class AdditionalInformation
{
    private ?int $user_id = null;
    private ?string $image = null;
    private ?string $address = null;

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


   
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }


    public function getByUserId(int $userId): ?self
    {
        $stmt = $this->db->prepare("SELECT * FROM additional_information WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            $info = new self($this->db);
            $info->user_id = $userId;
            $info->image = $row['image'] ?? null;
            $info->address = $row['address'] ?? null;
            return $info;
        }
    
        return null;
    }


    public function save(): bool
    {
        if ($this->user_id === null) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM additional_information WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $this->user_id]);
        $exists = $stmt->fetchColumn() > 0;

        if ($exists) {
            $stmt = $this->db->prepare("
                UPDATE additional_information 
                SET image = :image, address = :address 
                WHERE user_id = :user_id
            ");
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO additional_information (user_id, image, address) 
                VALUES (:user_id, :image, :address)
            ");
        }

        return $stmt->execute([
            ':user_id' => $this->user_id,
            ':image' => $this->image,
            ':address' => $this->address
        ]);
    }
}