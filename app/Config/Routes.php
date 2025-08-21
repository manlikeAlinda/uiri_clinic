<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    if (!session()->get('is_logged_in')) {
        return redirect()->to('/login');
    }
    return redirect()->to('/dashboard');
});


$routes->get('dashboard', 'DashboardController::index');
// Auth
$routes->get('login', 'Login::index');
$routes->post('login/authenticate', 'Login::authenticate');
$routes->get('logout', 'Login::logout');

$routes->get('register', 'Register::index');
$routes->post('register/save', 'Register::save');

// Doctor Management
$routes->get('doctors', 'DoctorController::index');
$routes->post('doctors/store', 'DoctorController::store');
$routes->post('doctors/update', 'DoctorController::update');
$routes->post('doctors/delete', 'DoctorController::delete');

// Equipment Management
$routes->get('equipment', 'EquipmentController::index');
$routes->post('equipment/store', 'EquipmentController::store');
$routes->post('equipment/update', 'EquipmentController::update');
$routes->post('equipment/delete', 'EquipmentController::delete');

// Supply Routes
$routes->get('supplies', 'SupplyController::index');
$routes->post('supplies/store', 'SupplyController::store');
$routes->post('supplies/update', 'SupplyController::update');
$routes->post('supplies/delete', 'SupplyController::delete');
$routes->get('supplies/report', 'SupplyController::generalReport');

// Drug Routes
$routes->get('drugs', 'DrugController::index');
$routes->post('drugs/store', 'DrugController::store');
$routes->post('drugs/update', 'DrugController::update');
$routes->post('drugs/delete', 'DrugController::delete');
// app/Config/Routes.php
$routes->get('drugs/report', 'DrugController::generalReport');


$routes->get('patients', 'PatientController::index');
$routes->post('patients/store', 'PatientController::store');
$routes->post('patients/update', 'PatientController::update');
$routes->post('patients/delete', 'PatientController::delete');

$routes->get('visits',         'VisitController::index');
$routes->post('visits/store',  'VisitController::store');
$routes->post('visits/update/(:num)', 'VisitController::update/$1');
$routes->post('visits/delete', 'VisitController::delete');

$routes->post('visitDetails/addDetails', 'VisitDetailsController::addDetails');
$routes->get('visits/details/(:num)', 'VisitController::getVisitDetails/$1');
$routes->post('visitDetails/updateDetail', 'VisitDetailsController::updateDetail');
$routes->get('visits/fetchEditDetails/(:num)', 'VisitDetailsController::fetchEditDetails/$1');

// User Management
$routes->get('users',          'UserController::index');
$routes->get('users/create',   'UserController::create');   // if you have a separate “new user” form
$routes->post('users/store',    'UserController::store');
$routes->post('users/update',   'UserController::update');
$routes->post('users/delete',   'UserController::delete');


// app/Config/Routes.php
$routes->get('reports', 'ReportsController::index');
$routes->get('reports/export/csv', 'ReportsController::exportCsv'); // optional export



$routes->get('getCsrfToken', 'SecurityController::getToken');
