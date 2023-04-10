<?php

namespace Salle\LSCoins\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class SignUpController
{

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function showForm(Request $request, Response $response): Response
    {

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render(
            $response,
            'sign-up.twig',
            [
                'formAction' => $routeParser->urlFor("handle-form"),
                'formMethod' => "POST"
            ]
        );
    }

    public function handleFormSubmission(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $errors = [];

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid';
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render(
            $response,
            'sign-up.twig',
            [
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor("handle-form"),
                'formMethod' => "POST"
            ]
        );
    }

}