<?php
require_once "../vendor/autoload.php";
require_once __DIR__ . '/../config/database.php';


use App\core\Session;
use App\core\Database;
use App\controllers\AuthController;
use App\controllers\ContactController;

Session::start();
$user = Session::get('user');

$page = $_GET['page'] ?? 'home';
$protectedPages = ['booking', 'history'];

if (in_array($page, $protectedPages) && !Session::has('user')) {
    Session::set('success', 'You must login first!');
    header("Location: ?page=login");
    exit;
}

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
    default => '404-Not Found'
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
        $controller = new AuthController($db);
        $controller->login();
        break;
  
    case 'register':
        require '../views/auth/register.php';
        break;



    case 'register_controller':
        $controller = new AuthController($db);
        $controller->register();
        break;

    case 'logout':
       $controller = new AuthController($db);
       $controller->logout();
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
    
    case 'contact_controller':
        $controller = new ContactController($db);
        $controller->submitContactForm();
        break;



    default:
        require '../views/errors/404.php';
        break;
}

include_once '../views/layouts/footer.php';









