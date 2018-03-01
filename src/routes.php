<?php

use App\Controllers\ForgotPasswordController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\UploadController;
use Slim\Slim;


$authenticatedMiddleware = function ($request, $response, $next) {
    return $_SESSION['username'] ? $next($request, $response) : $response->withRedirect('/login');
};
// Routes

$app->get('/', HomeController::class . ':show')->add($authenticatedMiddleware);
$app->get('/upload', UploadController::class . ':show')->add($authenticatedMiddleware);
$app->post('/upload', UploadController::class . ':upload')->add($authenticatedMiddleware);

$app->get('/login', LoginController::class . ':show');
$app->post( '/login', LoginController::class . ':login');
$app->get('/logout', LoginController::class . ':logout');

$app->get('/register', RegisterController::class . ':show');
$app->post('/register', RegisterController::class . ':register');

$app->get('/forgot', ForgotPasswordController::class . ':show');
$app->post('/forgot', ForgotPasswordController::class . ':forgot');
$app->get('/password-reset', \App\Controllers\ResetPasswordController::class . ':show');
$app->post('/password-reset', \App\Controllers\ResetPasswordController::class . ':reset');