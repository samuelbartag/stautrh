<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AuthRepository;

class LoginController extends DefaultController {

    private $db;
    private $requestMethod;

    private $users;
    private $auth;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->users = new UserRepository($db);
        $this->auth = new AuthRepository($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->login();
                break;

            default:
                $response = $this->notFoundResponse();
                break;

        }

        header($response['status_code_header']);
        if ($response['body'])
            echo $response['body'];

    }

    public function login()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $return = $this->validateData($input);
        if((bool) $return)
            return $this->notFoundResponse();
        
        $user = $this->users->findByEmailSenha($input['email'], $input['senha']);
        $token = $this->auth->registerToken($user['id']);

        $user['token'] = $token;

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($user, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);;

        return $response;
    }

    private function validateData($input)
    {
        $errors = [];

        if (! isset($input['senha']))
            $errors[] = 'Senha não informada';

        else {
            if (! isset($input['email']))
                $errors[] = 'E-mail não informado';

            else {
                $return = $this->users->findByEmail($input['email']);
                if (! (bool) $return )
                    $errors[] = 'E-mail não encontrado';
                    
                else {
                    $return = $this->users->findByEmailSenha($input['email'], $input['senha']);
                    if (! (bool) $return )
                        $errors[] = 'Senha inválida';
                }
            }
        }

        return $errors;
    }

}