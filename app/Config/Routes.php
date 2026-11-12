<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---- Tamu (belum login) ----
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::attemptRegister');
});

// Registrasi admin: dilindungi kode rahasia (lihat .env -> admin.registrationCode)
$routes->get('admin/register', 'Auth::adminRegister');
$routes->post('admin/register', 'Auth::attemptAdminRegister');

$routes->get('logout', 'Auth::logout');

// ---- Pengguna login ----
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');

    $routes->get('laporan/buat', 'Laporan::buat');
    $routes->post('laporan/simpan', 'Laporan::simpan');
    $routes->get('laporan/(:num)', 'Laporan::detail/$1');
});

// ---- Admin ----
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->post('status', 'Admin::updateStatus');
});
