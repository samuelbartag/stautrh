<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\DrinkRepository;

class UserController extends DefaultController {

    private $db;
    private $requestMethod;
    private $id;
    private $drink;

    private $usersRepo;
    private $drinkRepo;

    public function __construct($db, $requestMethod, $id, $drink=false)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;
        $this->drink = $drink;

        $this->usersRepo = new UserRepository($db);
        $this->drinkRepo = new DrinkRepository($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $this->validateToken($this->db);

                if ($this->id) {
                    $response = $this->getUser($this->id);
                } else {
                    $response = $this->getAllUsers();
                };
                break;

            case 'POST':
                $this->validateToken($this->db);

                $response = $this->createUser($this->drink);
                break;

            case 'PUT':
                $this->validateToken($this->db, $this->id);

                $response = $this->updateUser($this->id);
                break;

            case 'DELETE':
                $this->validateToken($this->db, $this->id);

                $response = $this->deleteUser($this->id);
                break;

            default:
                $response = $this->notFoundResponse();
                break;

        }

        header($response['status_code_header']);
        if ($response['body'])
            echo $response['body'];

    }

    private function getAllUsers()
    {
        $filters = Array();

        if (isset($_GET['all']))
            $filters['all'] = (bool) true;

        if (isset($_GET['limit']) && is_numeric($_GET['limit']))
            $filters['limit'] = (int) $_GET['limit'];

        if (isset($_GET['offset']) && is_numeric($_GET['offset']))
            $filters['offset'] = (int) $_GET['offset'];

        $result = $this->usersRepo->findAll( $filters );
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);

        return $response;

    }

    private function getUser($id)
    {
        $result = $this->usersRepo->findById($id);
        if (! $result)
            return $this->notFoundResponse();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);

        return $response;

    }

    private function createUser($drink)
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $input['drink'] = $drink;
        $input['idUser'] = $this->id;

        if((bool) $input['idUser']) {
            $result = $this->usersRepo->findById($input['idUser']);

            if (! $result)
                return $this->notFoundResponse();
        }

        $return = $this->validateData($input);
        if ((bool) $return)
            return $this->unprocessableEntityResponse($return);

        if($drink)
            $this->drinkRepo->insert($input);
        else
            $this->usersRepo->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;

        return $response;

    }

    private function updateUser($id)
    {
        $result = $this->usersRepo->findById($id);
        if (! $result)
            return $this->notFoundResponse();

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $return = $this->validateData($input);
        if ((bool) $return)
            return $this->unprocessableEntityResponse($return);

        $this->usersRepo->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;

        return $response;

    }

    private function deleteUser($id)
    {
        $result = $this->usersRepo->findById($id);
        if (! $result)
            return $this->notFoundResponse();

        $this->usersRepo->delete($id);
        $this->drinkRepo->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;

        return $response;

    }

    private function validateData($input)
    {
        $errors = [];

        if (isset($input['drink']) && !$input['drink']) {
            if (! isset($input['name']))
                $errors[] = 'Nome não informado';

            if (! isset($input['email']))
                $errors[] = 'E-mail não informado';

            else {
                if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL))
                    $errors[] = 'E-mail inválido';

                if ($this->usersRepo->findByEmail($input['email']))
                    $errors[] = 'E-mail já cadastrado';
            }

            if (! isset($input['password']))
                $errors[] = 'Senha não informada';

        } elseif (isset($input['drink']) && $input['drink']) {
            if (! isset($input['drink_count']))
              $errors[] = 'Quantidade de líquido ingerido não informado';
        }

        return $errors;

    }

}
