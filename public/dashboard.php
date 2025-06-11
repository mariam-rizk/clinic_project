<?php
require_once "../vendor/autoload.php";
require_once __DIR__ . '/../config/database.php';

use App\core\Session;
use App\core\Database;


Session::start();

$page = $_GET['page'] ?? 'dashboard';


 $publicPages = ['admin_login', 'login_controller', 'logout'];

// if (!in_array($page, $publicPages) && !Session::has('admin')) {
//     Session::set('info', 'Admin login required!');
//     header("Location: dashboard.php?page=admin_login");
//     exit;
// }

$db = Database::getInstance($config)->getConnection();


$pageTitle = match ($page) {
    'dashboard'         => 'Admin Dashboard',
    'manage_users'      => 'Manage Users',
    'manage_bookings'   => 'Manage Bookings',
    'admin_login'       => 'Administrative Panel',
    'manage_majors'     => 'Manage Majors',
    'manage_doctors'    => 'Manage Doctors',
    'manage_contacts'   => 'Manage Contacts',
    default             => '404 - Page Not Found'
};


if (!in_array($page, $publicPages)) {
    include_once '../views/dashboard/layouts/header.php';
}

switch ($page) {
   
    case 'admin_login':
        require '../views/dashboard/auth/login.php';
        break;


  
    case 'dashboard':
        require '../views/dashboard/home.php';
        break;

    case 'manage_users':
        require '../views/dashboard/users/users.php';
        break;

    case 'manage_bookings':
        require '../views/dashboard/bookings/bookings.php';
        break;

    case 'manage_majors':
        require '../views/dashboard/majors/majors.php';
        break;

    case 'manage_doctors':
        require '../views/dashboard/doctors/doctors.php';
        break;

    case 'manage_contacts':
        require '../views/dashboard/contacts/contacts.php';
        break;

    default:
        require '../views/errors/404.php';
        break;
}


if (!in_array($page, $publicPages)) {
    include_once '../views/dashboard/layouts/footer.php';
}
