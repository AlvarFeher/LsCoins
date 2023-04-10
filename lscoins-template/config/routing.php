<?php
declare(strict_types=1);
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\LSCoins\Controller\HomeController;
use Salle\LSCoins\Controller\SignInController;
use Salle\LSCoins\Controller\SignUpController;


$app->get('/', HomeController::class . ':apply')->setName('root');

$app->get(
    '/login',
    SignInController::class . ":showForm"
);

$app->post(
    '/login',
    SignInController::class . ":handleFormSubmission"
)->setName('handle-form');

$app->get(
    '/signup',
    SignUpController::class . ":showForm"
);

$app->post(
    '/signup',
    SignUpController::class . ":handleFormSubmission"
)->setName('handle-form');


$app->get(
    '/home',
    function (Request $request, Response $response) {
        return $this->get('view')->render($response, 'home.twig', []);
    }
)->setName('/home');

$app->post(
    '/user',
    CreateUserController::class . ":apply"
)->setName('create_user');