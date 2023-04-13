<?php

namespace Salle\LSCoins\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class MarketController
{
    private Twig $twig;

    // You can also use https://stitcher.io/blog/constructor-promotion-in-php-8
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function apply(Request $request, Response $response)
    {
        return $this->twig->render($response, 'market.twig');
    }
}