<?php
require_once "../vendor/autoload.php";
require_once "../config/database.php";


use App\core\Session;
use App\core\Database;

Session::start();

$page = $_GET['page'] ?? 'home';

// $publicPages = ['home', 'login', 'register', 'contact', 'doctors', 'majors'];

// if (!in_array($page, $publicPages) && !Session::has('user')) {
//     header("Location: index.php?page=login");
//     exit;
// }

$db = Database::getInstance($config)->getConnection();


$pageTitle = match ($page) {
    'home' => 'home page',
    'doctors' => 'doctors page',
    'majors' => 'majors page',
    'booking' => 'booking form',
    'contact' => 'contact us',
    'history' => 'history of bookings',
    'login' => 'login',
    'register' => 'register',
    default => 'Document'
};

include_once '../views/layouts/header.php';

switch ($page) {
    case 'home':
        require '../views/home.php';
        break;

    case 'login':
        require '../views/auth/login.php';
        break;

        
    case 'register':
        require '../views/auth/register.php';
        break;


    case 'doctors':
        require '../views/doctors/doctors.php';
        break;

    case 'majors':
        require '../views/majors/majors.php';
        break;

    case 'booking':
        require '../views/bookings/booking_form.php';
        break;

    case 'history':
        require '../views/bookings/history.php';
        break;

    case 'contact':
        require '../views/contact_us/contact_form.php';
        break;

    default:
        require '../views/errors/404.php';
        break;
}

include_once '../views/layouts/footer.php';