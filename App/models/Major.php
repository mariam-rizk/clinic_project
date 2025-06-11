<?php 

namespace App\models;

use PDO;
use PDOException;

class Major{
    private $id;
    private $name;
    private $description;
    

    public function __construct($id,$name,$description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
     
    }

    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getDescription(){
        return $this->description;
    }
    
    public static function create(PDO $pdo,$name,$description){

    }

    public static function getAll(PDO $pdo)
    {
        $stm = $pdo->prepare("SELECT * FROM `majors`");
        $stm->execute();
        $results = $stm->fetchAll(PDO::FETCH_ASSOC);
        $majors=[];
        foreach($results as $major){
            $majors[]= new Major($major['id'],$major['name'],$major['description']);
        }
        return $majors;
    }
   

}