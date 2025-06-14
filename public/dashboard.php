<?php
require_once "../vendor/autoload.php";
require_once __DIR__ . '/../config/database.php';

use App\core\Session;
use App\core\Database;
use App\controllers\dashboardControllers\MajorsController;
use App\controllers\dashboardControllers\BookingsController;
use App\controllers\dashboardControllers\AuthController;
use App\controllers\dashboardControllers\UserController;

Session::start(); 

$page = $_GET['page'] ?? 'dashboard';

$publicPages = ['admin_login', 'login_controller', 'admin_logout'];
$allowedPagesForDoctor = ['dashboard', 'manage_bookings', 'manage_contacts'];

if (!in_array($page, $publicPages)) {
    $user = Session::get('admin_user');

    if (!$user) {
        Session::set('info', 'Login required!');
        header("Location: dashboard.php?page=admin_login");
        exit;
    }

    $role = $user['role'] ?? '';

    if ($role === 'doctor' && !in_array($page, $allowedPagesForDoctor)) {
        Session::set('errors', ['Unauthorized access.']);
        header("Location: dashboard.php?page=dashboard");
        exit;
    }

    if (!in_array($role, ['admin', 'subadmin', 'doctor'])) {
        Session::set('errors', ['Unauthorized role.']);
        header("Location: dashboard.php?page=admin_login");
        exit;
    }
}

$db = Database::getInstance($config)->getConnection();

if ($page == 'doctor-controller') {
    require '../App/controllers/dashboardControllers/DoctorsController.php';

} elseif ($page == 'schedule-controller') {
    require '../App/controllers/dashboardControllers/ScheduleController.php';
}

switch ($page) {
    case 'login_controller':
        (new AuthController($db))->login();
        exit;

    case 'admin_logout':
        (new AuthController($db))->logout();
        exit;

    case 'create_user_controller':
        (new UserController($db))->createUser();
        break;

    case 'user_status':
        (new UserController($db))->Status();
        break;

    case 'update_role':
        (new UserController($db))->updateRole();
        exit;

    case 'delete_user':
        (new UserController($db))->deleteUser((int)$_GET['id']);
        exit;
}

$pageTitle = match ($page) {
    'dashboard'         => 'Admin Dashboard',
    'admin_login'       => 'Administrative Panel',
    'manage_users'      => 'Manage Users',
    'edit_user'         => 'Edit User',
    'create_user'       => 'Create User',
    'manage_bookings'   => 'Manage Bookings',
    'manage_majors'     => 'Manage Majors',
    'create_major'      => 'Create Specialization',
    'edit_major'        => 'Edit Specialization',
    'edit_booking'      => 'Edit Booking',
    'manage_doctors'    => 'Manage Doctors',
    'manage_contacts'   => 'Manage Contacts',
    'create-doctor'     => 'create-doctor',
    'edit-doctor'       => 'edit-doctor',
    'doctor-schedule'   => 'doctor-schedule',
    'create-appointment'=> 'create-appointment',
    'edit-appointment'  => 'edit-appointment',
    default             => '404 - Page Not Found'
};

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

    case 'edit_user':
        require '../views/dashboard/users/edit_user.php';
        break;

    case 'create_user':
        require '../views/dashboard/users/create_user.php';
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

    case 'create-doctor':
        require '../views/dashboard/doctors/create-doctor.php';
        break;

    case 'edit-doctor':
        require '../views/dashboard/doctors/edit-doctor.php';
        break;

    case 'doctor-schedule':
        require '../views/dashboard/doctors/doctor-schedule.php';
        break;

    case 'create-appointment':
        require '../views/dashboard/doctors/create-appointment.php';
        break;

    case 'edit-appointment':
        require '../views/dashboard/doctors/edit-appointment.php';
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

