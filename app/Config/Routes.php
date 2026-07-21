<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'ClientController::loginPage');
$routes->post('login', 'ClientController::login');

/* Gain */
$routes->get('gains', 'GainController::index');


/* Client - vues côté client */
$routes->get('accueil', 'DashboardController::index');

$routes->get('depot', 'TransactionController::faireDepot');
$routes->post('depot', 'TransactionController::enregistrerDepot');

$routes->get('retrait', 'TransactionController::faireRetrait');
$routes->post('retrait', 'TransactionController::enregistrerRetrait');

$routes->get('transfert', 'TransactionController::faireTransfert');
$routes->post('transfert', 'TransactionController::enregistrerTransfert');

$routes->get('solde', 'ClientController::solde');

$routes->get('historique', 'TransactionController::historique');

$routes->get(
    'transfert-multiple',
    'TransactionController::transfertMultiple'
);

$routes->post(
    'transfert-multiple',
    'TransactionController::enregistrerTransfertMultiple'
);

$routes->post(
    'transfert-multiple/calculer',
    'TransactionController::calculerFraisTransfertMultiple'
);
$routes->post('calculer-frais', 'TransactionController::calculerFrais');
$routes->get('operateur/login', 'OperateurController::loginPage');
$routes->post('operateur/login', 'OperateurController::login');

$routes->get('logout', 'OperateurController::logout');

/* Cote client */
$routes->group('client', ['filter' => 'auth:client'], function ($routes) {
    $routes->get('dashboard', 'ClientController::index');

});

/* Cote operateur */
$routes->group('operateur', ['filter' => 'auth:operateur'], function ($routes) {
    $routes->get('client', 'ClientController::getAllclient');

    $routes->get('historique2/(:num)', 'TransactionController::historique2/$1');

    /* CRUD prefixe */
    $routes->get('prefixes', 'PrefixController::index');
    $routes->get('prefixes/create', 'PrefixController::create');
    $routes->post('prefixes/store', 'PrefixController::store');
    $routes->get('prefixes/edit/(:num)', 'PrefixController::edit/$1');
    $routes->post('prefixes/update/(:num)', 'PrefixController::update/$1');
    $routes->get('prefixes/delete/(:num)', 'PrefixController::delete/$1');

    /* CRUD type operation */
    $routes->get('types-operations', 'TypeOperationController::index');
    $routes->get('types-operations/create', 'TypeOperationController::create');
    $routes->post('types-operations/store', 'TypeOperationController::store');
    $routes->get('types-operations/edit/(:num)', 'TypeOperationController::edit/$1');
    $routes->post('types-operations/update/(:num)', 'TypeOperationController::update/$1');
    $routes->get('types-operations/delete/(:num)', 'TypeOperationController::delete/$1');

    /* CRUD autres operateurs */
    $routes->get('autres_operateurs', 'AutreOperateurController::index');
    $routes->get('autres_operateurs/create', 'AutreOperateurController::create');
    $routes->post('autres_operateurs/store', 'AutreOperateurController::store');
    $routes->get('autres_operateurs/edit/(:num)', 'AutreOperateurController::edit/$1');
    $routes->post('autres_operateurs/update/(:num)', 'AutreOperateurController::update/$1');
    $routes->get('autres_operateurs/delete/(:num)', 'AutreOperateurController::delete/$1');

    $routes->get(
    'montants-operateurs',
    'TransactionController::montantsOperateurs'
    );

    /* CRUD commission */
    $routes->get('commissions', 'CommissionController::index');
    $routes->get('commissions/create', 'CommissionController::create');
    $routes->post('commissions/store', 'CommissionController::store');
    $routes->get('commissions/edit/(:num)', 'CommissionController::edit/$1');
    $routes->post('commissions/update/(:num)', 'CommissionController::update/$1');
    $routes->get('commissions/delete/(:num)', 'CommissionController::delete/$1');

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

    /* Gain */
    $routes->get('gains', 'GainController::index');

    /* Transaction */
    $routes->get('compte/historique/(:num)', 'CompteController::getHistorique/$1');
    $routes->get('transactions', 'TransactionController::index');
});
