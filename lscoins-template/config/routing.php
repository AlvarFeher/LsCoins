<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Salle\LSCoins\Controller\ChangePasswordController;
use Salle\LSCoins\Controller\CreateUserController;
use Salle\LSCoins\Controller\HomeController;
use Salle\LSCoins\Controller\MarketController;
use Salle\LSCoins\Controller\ProfileController;
use Salle\LSCoins\Controller\SignInController;
use Salle\LSCoins\Controller\SignUpController;

$app->get('/', HomeController::class . ':apply')->setName('root');

$app->get('/sign-in', SignInController::class . ":showForm");
$app->post('/sign-in', SignInController::class . ":handleFormSubmission")->setName('handle-form');

$app->get('/sign-up', SignUpController::class . ":showForm");
$app->post('/sign-up', SignUpController::class . ":handleFormSubmission")->setName('handle-form');

$app->get('/market', MarketController::class . ":apply");
$app->post('/market', MarketController::class . ":handleFormSubmission")->setName('handle-form');

$app->get('/profile', ProfileController::class . ":apply");
$app->post('/profile', ProfileController::class . ":apply")->setName('handle-form');

$app->get('/changePassword', ChangePasswordController::class . ":apply");
//$app->post('/changePassword', ChaController::class . ":handleFormSubmission")->setName('handle-form');

$app->get('/home', function (Request $request, Response $response) {return $this->get('view')->render($response, 'home.twig', []);})->setName('/home');

$app->post('/user', CreateUserController::class . ":apply")->setName('create_user');