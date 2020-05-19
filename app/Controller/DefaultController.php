<?php

namespace App\Controller;

use App\Repository\AuthRepository;

class DefaultController {

    private $authRepo;

    protected function unprocessableEntityResponse(Array $errors)
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => $errors
        ], JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);

        return $response;
    }

    protected function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;

        return $response;
    }

    protected function getToken()
    {
        switch(true) {
            case array_key_exists('HTTP_AUTHORIZATION', $_SERVER) :
                $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
                break;
            case array_key_exists('Authorization', $_SERVER) :
                $authHeader = $_SERVER['Authorization'];
                break;
            default :
                $authHeader = null;
                break;
        }
    
        try {
            preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
    
            if(!isset($matches[1])) {
                throw new \Exception('No Bearer Token');
            }
        } catch (\Exception $e) {
            return false;
        }
    
        return $matches[1];
    }

    protected function validateToken($db, $id = null)
    {
        $this->authRepo = new AuthRepository($db);

        $token = $this->getToken();

        if(! $this->authRepo->validateToken($token, $id)) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        } else {

        }
    
    }
    
}
