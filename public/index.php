<?php
require_once "../vendor/autoload.php";
require_once __DIR__ . '/../config/database.php';


use App\core\Session;
use App\core\Database;
use App\controllers\AuthController;
use App\controllers\ContactController;
use App\controllers\ProfileController;
use App\controllers\BookingController;
use App\controllers\HistoryController;
// use App\models\Doctor;
// use App\models\Major;

Session::start();
$user = Session::get('user');

$page = $_GET['page'] ?? 'home';
$protectedPages = ['booking', 'history', 'profile', 'upload_photo', 'additional_info', 'edit_profile'];

if (in_array($page, $protectedPages) && !Session::has('user')) {
    Session::set('info', 'You must login first!');
    header("Location: ?page=login");
    exit;
}

$db = Database::getInstance($config)->getConnection();


$pageTitle = match ($page) {
    'home'     => 'home page',
    'doctors'  => 'doctors page',
    'majors'   => 'majors page',
    'booking'  => 'booking form',
    'contact'  => 'contact us',
    'history'  => 'history of bookings',
    'login'    => 'login',
    'register' => 'register',
    'profile'  => 'profile',
    'upload_photo' => 'upload photo',
    'additional_info' => 'additional information',
    'edit_profile' => 'edit profile',	
    default    => '404-Not Found'
};

include_once '../views/layouts/header.php';

switch ($page) {
    case 'home':
        require '../views/home.php';
        break;

    case 'login':
        require '../views/auth/login.php';
        break;

    case 'login_controller':
        (new AuthController($db))->login();
        break;
  
    case 'register':
        require '../views/auth/register.php';
        break;

    case 'majors':
        require "../views/majors/majors.php";
        break;

    case 'doctors':
        require "../views/doctors/home.php";
        break;    

        
    case 'register_controller':
        (new AuthController($db))->register();
        break;


    case 'logout':
        (new AuthController($db))->logout();
        break;
      
    
    case 'profile':
        require '../views/users/profile.php';
        break;

    case 'upload_photo':
        require '../views/users/upload_photo.php';
        break;

    case 'edit_profile':
        require '../views/users/edit_profile.php';
        break;

    case 'additional_info':
        require '../views/users/additional_info.php';
        break;

    
    case 'upload_photo_controller':
        (new ProfileController($db))->upload_photo();
        break;

    case 'delete_photo':
        (new ProfileController($db))->delete_photo();
        break;

    case 'edit_profile_controller':
        (new ProfileController($db))->edit_profile();
        break;

    case 'additional_info_controller':
        (new ProfileController($db))->save_additional_info();
        break;
    
    case 'booking':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            Session::set('error', 'No doctor ID provided');
            header("Location: ?page=doctors");
            exit;
        }
        $controller = new BookingController($db); 
        $controller->create($id);
        break;

    case 'history':
    $controller = new HistoryController($db);
    $controller->index();
    break;

    case 'contact':
        require '../views/contact_us/contact_form.php';
        break;
    
    case 'contact_controller':
        (new ContactController($db))->submitContactForm();



    default:
        require '../views/errors/404.php';
        break;
}

include_once '../views/layouts/footer.php';









