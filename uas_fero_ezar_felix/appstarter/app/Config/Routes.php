<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Order::index');
$routes->get('items', 'ItemController::index');
$routes->get('items/create', 'ItemController::create');
$routes->post('items/store', 'ItemController::store');
