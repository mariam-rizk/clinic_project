<?php
require_once "../vendor/autoload.php";
require_once __DIR__ . '/../config/database.php';

use App\core\Session;
use App\core\Database;
use App\controllers\dashboardControllers\MajorsController;
use App\controllers\dashboardControllers\BookingsController;

session_start();

$page = $_GET['page'] ?? 'dashboard';

$publicPages = ['admin_login', 'login_controller', 'logout'];

$db = Database::getInstance($config)->getConnection();

$pageTitle = match ($page) {
    'dashboard'         => 'Admin Dashboard',
    'manage_users'      => 'Manage Users',
    'manage_bookings'   => 'Manage Bookings',
    'admin_login'       => 'Administrative Panel',
    'manage_majors'     => 'Manage Majors',
    'create_major'      => 'Create Specialization',
    'edit_major'        => 'Edit Specialization',
    'edit_booking'      => 'Edit Booking',
    'manage_doctors'    => 'Manage Doctors',
    'manage_contacts'   => 'Manage Contacts',
    default             => '404 - Page Not Found'
};

// تحميل header.php بس للصفحات اللي فعلاً بتعرض واجهة
$noOutputPages = ['store_major', 'update_major', 'delete_major', 'update_booking', 'delete_booking'];
if (!in_array($page, $publicPages) && !in_array($page, $noOutputPages)) {
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
        $bookingController = new BookingsController($db);
        $bookingController->index();
        require '../views/dashboard/bookings/bookings.php';
        break;

    case 'manage_majors':
        $majorController = new MajorsController($db);
        $majorController->index();
        require '../views/dashboard/majors/majors.php';
        break;

    case 'create_major':
        $majorController = new MajorsController($db);
        $majorController->create();
        require '../views/dashboard/majors/create-major.php';
        break;

    case 'store_major':
        $majorController = new MajorsController($db);
        $majorController->store();
        break;

    case 'edit_major':
        $majorController = new MajorsController($db);
        $majorController->edit();
        require '../views/dashboard/majors/edit-major.php';
        break;

    case 'update_major':
        $majorController = new MajorsController($db);
        $majorController->update();
        break;

    case 'delete_major':
        $majorController = new MajorsController($db);
        $majorController->delete();
        break;

    case 'edit_booking':
        $bookingController = new BookingsController($db);
        $bookingController->edit();
        require '../views/dashboard/bookings/edit_booking.php';
        break;

    case 'update_booking':
        $bookingController = new BookingsController($db);
        $bookingController->update();
        break;

    case 'delete_booking':
        $bookingController = new BookingsController($db);
        $bookingController->delete();
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

if (!in_array($page, $publicPages) && !in_array($page, $noOutputPages)) {
    include_once '../views/dashboard/layouts/footer.php';
}