<?php

session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Core/Autoloader.php';

use App\Core\Router;

$router = new Router();

// ===== RUTAS =====
// Auth
$router->get('/', 'AuthController', 'showLogin');
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');
$router->get('/forgot-password', 'AuthController', 'showForgotPassword');
$router->post('/forgot-password', 'AuthController', 'forgotPassword');
$router->get('/reset-password', 'AuthController', 'showResetPassword');
$router->post('/reset-password', 'AuthController', 'resetPassword');
$router->get('/verify-2fa', 'AuthController', 'showVerify2fa');
$router->post('/verify-2fa', 'AuthController', 'verify2fa');

// Label Generator (EtiquetadorOSL)
$router->get('/generator', 'LabelController', 'index', ['AuthMiddleware']);
$router->post('/generator/generate_pdf', 'LabelController', 'generatePdf', ['AuthMiddleware']);
// API Routes for Generator frontend
$router->get('/generator/get_saved_models', 'LabelController', 'getSavedModels', ['AuthMiddleware']);
$router->get('/generator/get_model', 'LabelController', 'getModel', ['AuthMiddleware']);
$router->get('/generator/delete_save_model', 'LabelController', 'deleteModel', ['AuthMiddleware']);
$router->get('/generator/edit_model_name', 'LabelController', 'editModelName', ['AuthMiddleware']);
$router->get('/generator/clear_preview', 'LabelController', 'clearPreview', ['AuthMiddleware']);

// Admin Dashboard
$router->get('/admin', 'AdminController', 'index', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/cpu', 'AdminController', 'cpu', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/gpu', 'AdminController', 'gpu', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/pc', 'AdminController', 'pc', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/sn', 'AdminController', 'sn', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/models', 'AdminController', 'models', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/users', 'AdminController', 'users', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/stats', 'AdminController', 'stats', ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/stats/json', 'AdminController', 'statsJson', ['AuthMiddleware', 'AdminMiddleware']);

// Admin POST routes (forms POST to same URL, controller handles GET vs POST internally)
$router->post('/admin/cpu', 'AdminController', 'cpu', ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/gpu', 'AdminController', 'gpu', ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/pc', 'AdminController', 'pc', ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/sn', 'AdminController', 'sn', ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/models', 'AdminController', 'models', ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/users', 'AdminController', 'users', ['AuthMiddleware', 'AdminMiddleware']);

// Dispatch request
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
