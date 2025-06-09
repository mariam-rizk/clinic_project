<?php

namespace App\models;

use PDO;

class AdditionalInformation
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM additional_information WHERE user_id = :user_id LIMIT 1
        ");
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getProfilePicture(int $userId): ?string
    {
        $stmt = $this->db->prepare("
            SELECT image FROM additional_information WHERE user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['image'] : null;
    }


    public function updateProfilePicture(int $userId, ?string $pictureName): bool
    {
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM additional_information WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $exists = $stmt->fetchColumn() > 0;
    
        if ($exists) {
          
            $stmt = $this->db->prepare("
                UPDATE additional_information
                SET image = :profile_picture
                WHERE user_id = :user_id
            ");
            return $stmt->execute([
                ':profile_picture' => $pictureName,
                ':user_id' => $userId
            ]);
        } else {
           
            $stmt = $this->db->prepare("
                INSERT INTO additional_information (user_id, image)
                VALUES (:user_id, :profile_picture) 
            ");
            return $stmt->execute([
                ':user_id' => $userId,
                ':profile_picture' => $pictureName
            ]);
        }
    }


    public function updateAddress(int $userId, string $address): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM additional_information WHERE user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $exists = $stmt->fetchColumn() > 0;
    
        if ($exists) {
            $stmt = $this->db->prepare("
                UPDATE additional_information SET address = :address WHERE user_id = :user_id
            ");
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO additional_information (user_id, address) VALUES (:user_id, :address)
            ");
        }
    
        return $stmt->execute([
            ':user_id' => $userId,
            ':address' => $address
        ]);
    }

    

}
    