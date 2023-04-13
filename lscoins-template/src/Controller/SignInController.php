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


    public function validatePassword($pwd):String{

        if(strlen($pwd) < 5){
            return "The password must contain at least 7 characters.";
        }
        if (!(strtolower($pwd) != $pwd && strtoupper($pwd) !=$pwd)) {
            return 'The password must contain both upper and lower case letters and numbers.';
        }
        if (!preg_match('~[0-9]+~', $pwd)) {
            return "The password must contain both upper and lower case letters and numbers.";
        }
        return "null";
    }

    public function handleFormSubmission(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errors = [];
        $error = false;
        $errors['password'] = $this->validatePassword($password);


        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid.';
            $error = true;
        }

        if(!$this->userExists($email)){
            $errors['email'] = 'User with this email address does not exist.';
            $error = true;
        }

        if(!str_contains($email,"@")){
            $errors['email'] = 'The email address is not valid.';
            $error = true;
        }

        if(!empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL)){
            $domain = substr(strrchr($email, "@"), 1);
            if(!strcmp($domain,"salle.url.edu" )==0){
                $errors['email'] = 'Only emails from the domain @salle.url.edu are accepted.';
                $error = true;
            }
        }
        var_dump($error);
        if($error == false){
            if($this->userRepository->isPasswordOkay($email,$password) && $this->validatePassword($password) == 'null'){
                session_start();
                $_SESSION['email'] = $email;
                $response = $response->withStatus(302)->withHeader('Location', '/');
            }else{
                $errors['email'] = 'Your email and/or password are incorrect.';
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