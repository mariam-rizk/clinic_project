<?php 

namespace App\models;
use PDO;
use PDOException;
class Schedule{
    private int $id ;
    private int $doctor_id;
    private $day_of_week;
    private $start_time;
    private $end_time;

    public function __construct($id,$doctor_id,$day_of_week,$start_time,$end_time)
    {
        $this->id = $id;
        $this->doctor_id = $doctor_id;
        $this->day_of_week = $day_of_week;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        
    }

    public function getScheduleId(){
        return $this->id;
    }

    public function getScheduleDoctorId(){
        return $this->doctor_id;
    }

    public function getDay(){
        return $this->day_of_week;
    }

    public function getStartTime(){
        return $this->start_time;
    }

    public function getEndTime(){
        return $this->end_time;
    }

    public static function getDoctorSchedule(PDO $pdo, int $doctor_id){
        $stm =$pdo->prepare("SELECT * FROM `doctor_schedule` WHERE doctor_id= :doctor_id");
        $stm->bindParam(':doctor_id',$doctor_id, PDO::PARAM_INT);
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $results = [];
        foreach($rows as $row){
            $results[]= new self($row['id'],$row['doctor_id'],$row['day_of_week'],$row['start_time'],$row['end_time']);
        }
        return $results;
    }

    public static function getById(PDO $pdo, $schedule_id)
    {
        $stm = $pdo->prepare("SELECT * FROM `doctor_schedule` WHERE id = :schedule_id");
        $stm->bindParam(":schedule_id", $schedule_id);
        $stm->execute();
        $schedule = $stm->fetch(PDO::FETCH_ASSOC);
        $schedule = new self($schedule['id'], $schedule['doctor_id'], $schedule['day_of_week'], $schedule['start_time'], $schedule['end_time']);

        return $schedule;
    }

    public static function create($pdo, $doctor_id,$day,$start_time,$end_time){
         $stm = $pdo->prepare("INSERT INTO doctor_schedule (`doctor_id`, `day_of_week`, `start_time`, `end_time`) VALUES (?, ?, ?, ?)");

        $stm->bindParam(1, $doctor_id);
        $stm->bindParam(2, $day);
        $stm->bindParam(3, $start_time);
        $stm->bindParam(4, $end_time);

        $query = $stm->execute();
        if ($query) {
            $id = $pdo->lastInsertId();
            return new Schedule($id, $doctor_id, $day, $start_time, $end_time);
        }

        return null;
    }


    public static function editSchedule($pdo, $id, $day, $start_time, $end_time){
        try{

            $stm = $pdo->prepare("UPDATE `doctor_schedule` SET `day_of_week` = :day, `start_time` = :start_time, `end_time` = :end_time WHERE `id` = :id");

            $stm->bindParam(':day', $day);
            $stm->bindParam(':start_time', $start_time);
            $stm->bindParam(':end_time', $end_time);
            $stm->bindParam(':id', $id);
            $stm->execute();
            if ($stm->rowCount() > 0) {
                    return true; 
                } else {
                    return false; 
                }
        }catch(PDOException $e){
            return "Error updating doctor: " . $e->getMessage();
        }
    }

    public static function deleteSchedule($pdo, $id){
        try{
            $stm = $pdo->prepare("DELETE FROM `doctor_schedule` WHERE id =:id");
            $stm->bindParam(":id",$id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                    return true; 
                } else {
                    return false; 
                }
        }catch(PDOException $e){
            return "Error deleting doctor: " . $e->getMessage();
        }
    }

}



?>