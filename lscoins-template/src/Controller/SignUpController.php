<?php

namespace Salle\LSCoins\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\LSCoins\Model\User;
use Salle\LSCoins\Model\UserRepository;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class SignUpController
{
    private UserRepository $userRepository;
    public function __construct(Twig $twig, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    public function showForm(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'sign-up.twig');
        //$routeParser = RouteContext::fromRequest($request)->getRouteParser();
       // return $this->twig->render($response, 'sign-up.twig', ['formAction' => $routeParser->urlFor("handle-form"),
               // 'formMethod' => "POST"]

       // );
    }

    public function validateEmail($email):String{
        if (!empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $domain = substr(strrchr($email, "@"), 1);
            if(strcmp($domain,"salle.url.edu" )==0){
                return 'true';
            }else{
                return 'Only emails from the domain @salle.url.edu are accepted.';
            }
        }else{
            return 'The email address is not valid.';
        }
    }

    public function validatePassword($pwd,$repeatPwd):String{
        if(strcmp($pwd,$repeatPwd) != 0){
            return "Passwords do not match.";
        }
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

    public function addUser($email,$pwd,$coins):void{
        $user = new User($email,$pwd,$coins,new \DateTime(),new \DateTime());
        $this->userRepository->save($user);
    }

    public function handleFormSubmission(Request $request, Response $response): Response
    {
        $error = false;
        $data = $request->getParsedBody();
        $errors = [];
        $message = $this->validateEmail($data['email']);

        $email = $_POST['email'];
        $password = $_POST['password'];
        $repassword = $_POST['repeatPassword'];
        $coins = $_POST['coins'];
        // not valid
        if(strcmp($message,"true") != 0){
            $errors['email'] = $message;
            $error = true;
        }
        $message = $this->validatePassword($password,$repassword);
        if(strcmp($message,"null")!=0){
            $errors['password'] = $message;
            $error = true;
        }

        if($this->userRepository->userExists($email)){
            $errors['email'] = "The email address is not valid.";
            $error = true;
        }

       $routeParser = RouteContext::fromRequest($request)->getRouteParser();
       //var_dump($data['email']);

       if($error == false){
           $this->addUser($email,$password,$coins);
           //header('Location: /sign-in');
           $response = $response->withStatus(302)->withHeader('Location', '/sign-in');
           return $response;
       }

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