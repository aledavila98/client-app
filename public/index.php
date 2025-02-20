<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if ($_ENV['APP_ENV'] === 'dev') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$requestUri = $_SERVER['REQUEST_URI'];

if (str_contains($requestUri, 'app/')) {
    include __DIR__ . '/../src/view.php';
} else if (str_contains($requestUri, 'api/')) {
    include __DIR__ . '/../src/server.php';
} else {
    http_response_code(404);
    echo '404 Not Found';
}