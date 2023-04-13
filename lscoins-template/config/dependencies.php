<?php

use Salle\LSCoins\Controller\CreateUserController;
use Salle\LSCoins\Controller\MarketController;
use Salle\LSCoins\Controller\SignInController;
use Salle\LSCoins\Controller\SignUpController;
use DI\Container;
use Psr\Container\ContainerInterface;
use Salle\LSCoins\Controller\HomeController;
use Salle\LSCoins\Model\Repository\MySQLUserRepository;
use Salle\LSCoins\Model\Repository\PDOSingleton;
use Salle\LSCoins\Model\UserRepository;
use Slim\Views\Twig;


$container = new Container();
$container->set(
    'view',
    function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    }
);

$container->set(
    SignInController::class,
    function (ContainerInterface $c) {
        $controller = new SignInController($c->get("view"),$c->get(UserRepository::class));
        return $controller;
    }
);

$container->set(
    SignUpController::class,
    function (ContainerInterface $c) {
        $controller = new SignUpController($c->get("view"),$c->get(UserRepository::class));
        return $controller;
    }
);

$container->set(
    MarketController::class,
    function (ContainerInterface $c) {
        $controller = new MarketController($c->get("view"),$c->get(UserRepository::class));
        return $controller;
    }
);

$container->set(
    HomeController::class,
    function (ContainerInterface $c) {
        $controller = new HomeController($c->get("view"));
        return $controller;
    }
);

$container->set('db', function () {
    return PDOSingleton::getInstance(
        $_ENV['MYSQL_ROOT_USER'],
        $_ENV['MYSQL_ROOT_PASSWORD'],
        $_ENV['MYSQL_HOST'],
        $_ENV['MYSQL_PORT'],
        $_ENV['MYSQL_DATABASE']
    );
});

$container->set(UserRepository::class, function (ContainerInterface $container) {
    return new MySQLUserRepository($container->get('db'));
});

$container->set(
    CreateUserController::class,
    function (Container $c) {
        $controller = new CreateUserController($c->get("view"), $c->get(UserRepository::class));
        return $controller;
    }
);

?>