<?php
declare(strict_types=1);

// Let PHP's built-in server serve existing CSS/JS/image files directly.
if (PHP_SAPI === 'cli-server') {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
    $staticFile = __DIR__ . $requestedPath;
    if ($requestedPath !== '/' && is_file($staticFile)) {
        return false;
    }
}

session_start();

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Core/helpers.php';
require BASE_PATH . '/app/Core/Database.php';
require BASE_PATH . '/app/Core/Auth.php';
require BASE_PATH . '/app/Core/Validator.php';
require BASE_PATH . '/app/Core/Router.php';
require BASE_PATH . '/app/Models/User.php';
require BASE_PATH . '/app/Models/Note.php';
require BASE_PATH . '/app/Controllers/AuthController.php';
require BASE_PATH . '/app/Controllers/NoteController.php';
require BASE_PATH . '/app/Controllers/SearchController.php';
require BASE_PATH . '/app/Controllers/AdminController.php';

Database::initialize();

$router = new Router();
require BASE_PATH . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
