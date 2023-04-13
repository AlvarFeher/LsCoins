<?php

namespace Salle\LSCoins\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class ProfileController
{
    private Twig $twig;


    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function apply(Request $request, Response $response)
    {
        return $this->twig->render($response, 'profile.twig');
    }
}