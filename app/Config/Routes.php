<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');
$routes->get('/login', 'Auth::loginPage');

$routes->get('/dashboard', 'DashboardController::index');

$routes->get('/register', 'Auth::registerPage');
$routes->post('/register', 'Auth::register');