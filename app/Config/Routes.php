<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ClientController::loginPage');
$routes->post('login' , 'ClientController::login') ;

/* CRUD prefixe */
$routes->get('prefixes', 'PrefixController::index');
$routes->get('prefixes/create', 'PrefixController::create');
$routes->post('prefixes/store', 'PrefixController::store');
$routes->get('prefixes/edit/(:num)', 'PrefixController::edit/$1');
$routes->post('prefixes/update/(:num)', 'PrefixController::update/$1');
$routes->get('prefixes/delete/(:num)', 'PrefixController::delete/$1');
