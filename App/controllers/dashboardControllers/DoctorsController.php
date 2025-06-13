<?php
use App\models\Doctor;
use App\traits\ManageImage;

if($_SERVER['REQUEST_METHOD'] ="POST" ){
    $action= $_GET['action'];
    if($action == 'add'){
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $gender = trim($_POST['gender']);
        $major = trim($_POST['major']);
        $image = $_FILES['image'];
        $img_name =null;
        if($image){
            $img_name = ManageImage::uploadFile($image,'doctors');
        }
        $bio = trim($_POST['bio']);
        $password = trim($_POST['password']);
        $newDoctor = Doctor::createDoctor($db, $name, $email, $password, $phone, $gender, $img_name, $major, $bio);
        if($newDoctor){
            header("Location: dashboard.php?page=manage_doctors");
            exit;
        }
    }elseif($action == 'edit'){
        print_r($_POST);
        $doctor_id =trim($_POST['doctor_id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $gender = trim($_POST['gender']);
        $major = trim($_POST['major']);
        $image = $_FILES['image']['name'];
        $bio = trim($_POST['bio']);
        $update = Doctor::editDoctor($db,$doctor_id, $name, $email, $phone, $gender, $image, $major, $bio);
       
        if($del){
            //  delete doctor successfully
        }else{
            //  error can not delete doctor   
        }
        header("Location: dashboard.php?page=manage_doctors");
        exit();

    }elseif($action == 'delete'){
        $doctor_id =trim($_POST['doctor_id']);
        $del = Doctor::deleteDoctor($db,$doctor_id);
        // print_r($del);
        if($del){
            //  delete doctor successfully
        }else{
            //  error can not delete doctor   
        }
        header("Location: dashboard.php?page=manage_doctors");
        exit();

    }
    

}


?>