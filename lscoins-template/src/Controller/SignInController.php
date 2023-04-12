<?php

namespace Salle\LSCoins\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\LSCoins\Model\UserRepository;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class SignInController
{
    private UserRepository $userRepository;

    public function __construct(Twig $twig, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->userRepository=$userRepository;
    }

    public function showForm(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $this->twig->render(
            $response,
            'sign-in.twig',
            [
                'formAction' => $routeParser->urlFor("handle-form"),
                'formMethod' => "POST"
            ]
        );
    }

    private function userExists($email):bool{
        return $this->userRepository->userExists($email);
    }

    private function checkPassword($email,$pwd):bool{
        return $this->userRepository->isPasswordOkay($email,$pwd);
    }

    public function handleFormSubmission(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errors = [];
        $error = false;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid';
            $error = true;
        }

        if(!$this->userExists($email)){
            $errors['email'] = 'The user does not exist';
            $error = true;
        }

        if($error == false){
            if($this->userRepository->isPasswordOkay($email,$password)){
                $_SESSION['email'] = $email;
                $response = $response->withStatus(302)->withHeader('Location', '/');
            }else{
                $errors['email'] = 'wrong credentials';
            }
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render(
            $response,
            'sign-in.twig',
            [
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor("handle-form"),
                'formMethod' => "POST"
            ]
        );


    }


}