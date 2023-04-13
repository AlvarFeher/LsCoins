<?php
declare(strict_types=1);

namespace Salle\LSCoins\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class HomeController
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function apply(Request $request, Response $response)
    {
        session_start();
        $email =  $_SESSION['email'];
        $username = explode('@',$email);

        if(!empty($_GET['logoutButton'])){
            session_destroy();
            header('Location: /sign-in');
            exit;
        }
        if(!empty($_GET['market'])){
            header('Location: /market');
            exit;
        }
        if(!empty($_GET['profile'])){
            header('Location: /profile');
            exit;
        }
        return $this->twig->render($response, 'homescreen.twig',['email' =>$username[0]]);
    }
}