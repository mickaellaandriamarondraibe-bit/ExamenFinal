<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/* CRUD prefixe */
$routes->get('prefixes', 'PrefixController::index');
$routes->get('prefixes/create', 'PrefixController::create');
$routes->post('prefixes/store', 'PrefixController::store');
$routes->get('prefixes/edit/(:num)', 'PrefixController::edit/$1');
$routes->post('prefixes/update/(:num)', 'PrefixController::update/$1');
$routes->get('prefixes/delete/(:num)', 'PrefixController::delete/$1');

/* CRUD type operation */
$routes->get('types-operations','TypeOperationController::index');
$routes->get('types-operations/create','TypeOperationController::create');
$routes->post('types-operations/store','TypeOperationController::store');
$routes->get('types-operations/edit/(:num)','TypeOperationController::edit/$1');
$routes->post('types-operations/update/(:num)','TypeOperationController::update/$1');
$routes->get('types-operations/delete/(:num)','TypeOperationController::delete/$1');

/* CRUD bareme */
$routes->get('baremes-frais', 'BaremeFraisController::index');
$routes->get('baremes-frais/create', 'BaremeFraisController::create');
$routes->post('baremes-frais/store', 'BaremeFraisController::store');
$routes->get('baremes-frais/edit/(:num)', 'BaremeFraisController::edit/$1');
$routes->post('baremes-frais/update/(:num)', 'BaremeFraisController::update/$1');
$routes->get('baremes-frais/delete/(:num)', 'BaremeFraisController::delete/$1');

/* CRUD compte */
$routes->get('compte', 'CompteController::index');
$routes->get('compte/create', 'CompteController::create');
$routes->post('compte/store', 'CompteController::store');
$routes->get('compte/edit/(:num)', 'CompteController::edit/$1');
$routes->post('compte/update/(:num)', 'CompteController::update/$1');
$routes->get('compte/delete/(:num)', 'CompteController::delete/$1');

/* Client */
$routes->get('/', 'ClientController::loginPage');
$routes->post('login' , 'ClientController::login') ;

/* Gain */
$routes->get('gains', 'GainController::index');