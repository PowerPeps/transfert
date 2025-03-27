<?php
// Point d'entrée de l'application
require_once 'config/config.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Database.php';
require_once 'core/Auth.php';
require_once 'core/Permissions.php';

// Charger les modèles
require_once 'models/UserModel.php';
require_once 'models/FileModel.php';
require_once 'models/GroupModel.php';

// Démarrer la session
session_start();

// Initialiser le routeur
$router = new Router();

// Définir les routes
$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/login', 'AuthController@loginForm');
$router->addRoute('POST', '/login', 'AuthController@login');
$router->addRoute('GET', '/register', 'AuthController@registerForm');
$router->addRoute('POST', '/register', 'AuthController@register');
$router->addRoute('GET', '/logout', 'AuthController@logout');

// Routes du tableau de bord
$router->addRoute('GET', '/dashboard', 'DashboardController@index');

// Routes des fichiers
$router->addRoute('GET', '/files', 'FileController@index');
$router->addRoute('GET', '/files/create', 'FileController@createForm');
$router->addRoute('POST', '/files/create', 'FileController@create');
$router->addRoute('GET', '/files/edit/:id', 'FileController@editForm');
$router->addRoute('POST', '/files/edit/:id', 'FileController@update');
$router->addRoute('POST', '/files/delete/:id', 'FileController@delete');
$router->addRoute('GET', '/download/:uuid', 'FileController@download');

// Routes de gestion des utilisateurs
$router->addRoute('GET', '/users', 'UserController@index');
$router->addRoute('GET', '/users/create', 'UserController@createForm');
$router->addRoute('POST', '/users/create', 'UserController@create');
$router->addRoute('GET', '/users/edit/:id', 'UserController@editForm');
$router->addRoute('POST', '/users/edit/:id', 'UserController@update');
$router->addRoute('POST', '/users/delete/:id', 'UserController@delete');

// Routes de gestion des groupes
$router->addRoute('GET', '/groups', 'GroupController@index');
$router->addRoute('GET', '/groups/create', 'GroupController@createForm');
$router->addRoute('POST', '/groups/create', 'GroupController@create');
$router->addRoute('GET', '/groups/edit/:id', 'GroupController@editForm');
$router->addRoute('POST', '/groups/edit/:id', 'GroupController@update');
$router->addRoute('POST', '/groups/delete/:id', 'GroupController@delete');

// Dispatcher la requête
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

