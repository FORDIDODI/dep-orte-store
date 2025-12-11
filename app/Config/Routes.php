<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Routes
$routes->get('/', 'Home::index');
$routes->get('game/(:segment)', 'Home::game/$1');
$routes->post('order/create', 'Order::create');
$routes->get('order/status/(:segment)', 'Order::status/$1');
$routes->post('order/check-promo', 'Order::checkPromo');
$routes->post('order/upload-payment-proof/(:segment)', 'Order::uploadPaymentProof/$1');
$routes->get('order/payment-proof/(:segment)', 'Order::viewPaymentProof/$1');
$routes->get('cek-transaksi', 'Home::cekTransaksi');
$routes->post('cek-transaksi/search', 'Home::searchTransaction');

// Auth Routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::attemptRegister');
    $routes->get('logout', 'Auth::logout');
});

// User Dashboard (Login Required)
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('transactions', 'Dashboard::transactions');
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('profile/update', 'Dashboard::updateProfile');
});

// Admin Routes
$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login', 'Admin\Auth::attemptLogin');
$routes->get('admin/logout', 'Admin\Auth::logout');

$routes->group('admin', ['filter' => 'adminauth'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    
    // Transactions
    $routes->get('transactions', 'Admin\Transactions::index');
    $routes->get('transactions/detail/(:num)', 'Admin\Transactions::detail/$1');
    $routes->post('transactions/update-status', 'Admin\Transactions::updateStatus');
    
    // Games
    $routes->get('games', 'Admin\Games::index');
    $routes->get('games/create', 'Admin\Games::create');
    $routes->post('games/store', 'Admin\Games::store');
    $routes->get('games/edit/(:num)', 'Admin\Games::edit/$1');
    $routes->post('games/update/(:num)', 'Admin\Games::update/$1');
    $routes->post('games/delete/(:num)', 'Admin\Games::delete/$1');
    
    // Products
    $routes->get('products', 'Admin\Products::index');
    $routes->post('products/store', 'Admin\Products::store');
    $routes->post('products/update/(:num)', 'Admin\Products::update/$1');
    $routes->post('products/delete/(:num)', 'Admin\Products::delete/$1');
    
    // Promos
    $routes->get('promos', 'Admin\Promos::index');
    $routes->post('promos/store', 'Admin\Promos::store');
    $routes->post('promos/update/(:num)', 'Admin\Promos::update/$1');
    $routes->post('promos/delete/(:num)', 'Admin\Promos::delete/$1');
});