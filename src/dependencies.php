<?php
// DIC configuration
use App\Controllers\ForgotPasswordController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\ResetPasswordController;
use App\Controllers\UploadController;
use App\Services\MailerService;
use Mailgun\Mailgun;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['mailgun'] = function ($c) {
    $apiKey = $c['settings']['mailgun']['apiKey'];
    return Mailgun::create($apiKey);
};

$container['mailer'] = function ($c) {
    $settings = $c['settings']['mailgun'];
    $domain = $settings['domain'];
    $from = $settings['from'];
    $mailgun = $c->get('mailgun');
    return new MailerService($mailgun, $domain, $from);
};

//Controllers


//Generic controllers
loadControllers($container, [
    HomeController::class,
    UploadController::class,
    RegisterController::class,
    LoginController::class,
    ResetPasswordController::class
]);

//Custom controllers

$container[ ForgotPasswordController::class] = function($c) {
    $renderer = $c->get('renderer');
    $db = $c->get('db');
    $mailer = $c->get('mailer');
    return new  ForgotPasswordController($renderer, $db, $mailer);
};

function loadControllers($container, $controllers) {
    foreach( $controllers as $controllerClass) {
        $container[$controllerClass] = function($c) use ($controllerClass) {
            $renderer = $c->get('renderer');
            $db = $c->get('db');
            return new $controllerClass($renderer, $db);
        };
    }
}
