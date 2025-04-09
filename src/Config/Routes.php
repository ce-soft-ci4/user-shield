<?php

namespace UserShield\UserModule\Config;

if (!isset($routes)) {
    $routes = \Config\Services::routes();
}

// Charge la configuration du module
$config = config('UserShield\UserModule\Config\UserModule');

// Groupe de routes avec préfixe
$routes->group('', ['filter' => 'group:admin', 'namespace' => 'UserShield\UserModule\Controllers'], function($routes) {
    $routes->get('users', 'UserController::index');
    $routes->get('users/new', 'UserController::new');
    $routes->post('users/create', 'UserController::create');
    $routes->get('users/edit/(:num)', 'UserController::edit/$1');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->get('users/disable/(:num)', 'UserController::disable/$1');
    $routes->get('users/enable/(:num)', 'UserController::enable/$1');
    $routes->get('users/delete/(:num)', 'UserController::delete/$1');
});

$routes->group('', ['filter' => 'auth', 'namespace' => 'UserShield\UserModule\Controllers'], function($routes) {
    $routes->get('profile', 'UserController::profile');
    $routes->post('profile/update', 'UserController::updateProfile');
});

$routes->group('', ['namespace' => 'UserShield\UserModule\Controllers'], function($routes) {
    $routes->get('forgot-password', 'UserController::forgotPassword');
    $routes->post('forgot-password', 'UserController::processForgotPassword');
    $routes->get('reset-password', 'UserController::resetPassword');
    $routes->post('reset-password', 'UserController::processResetPassword');
});
