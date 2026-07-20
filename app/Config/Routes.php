<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ClientController::loginPage');
$routes->post('login' , 'ClientController::login') ;