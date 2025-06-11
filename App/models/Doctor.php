<?php 
namespace App\models;

use PDO;
use PDOException;

class Doctor{
    private $id;
    private $name;
    private $email;
    private $password;
    private $phone;
    private $gender;
    private $image;
    private $major_id;
    private $bio;

    public function __construct($id,$name,$email,$password,$phone,$gender,$image,$major_id,$bio)
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

    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPhone(){
        return $this->phone;
    }
    public function getGender(){
        return $this->gender;
    }
    public function getImage(){
        return $this->image;
    }
    public function getMajorId($pdo){
      try {
          $stm = $pdo->prepare("SELECT name from majors WHERE id= :id");
        $stm->bindParam(":id",$this->major_id);
        $stm->execute();
        $major = $stm->fetch(PDO::FETCH_ASSOC);
        return $major['name']? $major['name']: null;

      } catch (PDOException $e) {
        echo "Error fetching major name: " . $e->getMessage();
        return null;
      }
    }
     public function getBio(){
        return $this->bio;
    }

    public static function create(PDO $pdo,$name,$email,$password,$phone,$gender,$image,$major_id,$bio){

    }

    public static function getAll(PDO $pdo)
    {
        $stm = $pdo->prepare("SELECT * FROM `doctors`");
        $stm->execute();
        $results = $stm->fetchAll(PDO::FETCH_ASSOC);
        $doctors=[];
        foreach($results as $doctor){
            $doctors[]= new Doctor($doctor['id'],$doctor['name'],$doctor['email'],$doctor['password'],$doctor['phone'],$doctor['gender'],$doctor['image'],$doctor['major_id'],$doctor['bio']);

        }
        return $doctors;
    }

    public static function getByMajor(PDO $pdo ,$major)
    {
       $stm = $pdo->prepare("SELECT * FROM doctors WHERE major_id = (SELECT id FROM majors WHERE name= :major)");
       $stm->bindParam(":major",$major);
       $stm->execute();
       $results = $stm->fetchAll(PDO::FETCH_ASSOC);
       $doctors =[];
       foreach($results as $doctor){
            $doctors[]= new Doctor($doctor['id'],$doctor['name'],$doctor['email'],$doctor['password'],$doctor['phone'],$doctor['gender'],$doctor['image'],$doctor['major_id'],$doctor['bio']);        
       }
       return $doctors;
    }

    public static function getById(PDO $pdo ,$id)
    {
        $stm = $pdo->prepare("SELECT * FROM doctors WHERE id = :id");
        $stm->bindParam(":id",$id);
        $stm->execute();
        $doctor = $stm->fetch(PDO::FETCH_ASSOC);
        $doctor= new Doctor($doctor['id'],$doctor['name'],$doctor['email'],$doctor['password'],$doctor['phone'],$doctor['gender'],$doctor['image'],$doctor['major_id'],$doctor['bio']);        
       
       return $doctor;
    }

   

}