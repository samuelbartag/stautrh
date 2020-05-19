<?php

namespace App\Repository;

class AuthRepository {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    function registerToken($id)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(64));
        
        $query = "
            UPDATE users
            SET 
                token = :token
            WHERE id = :id;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'token' => $token,
                'id' => $id
            ]);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if(! (bool) $result);
                return $token;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    function validateToken($token, $id)
    {
        $query = "
            SELECT 
                token
            FROM
                users
            WHERE token = :token";
        $params['token'] = $token;

        if ((bool) $id) {
            $query .= " AND id = :id";
            $params['id'] = $id;
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return (bool) $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

}
