<?php
namespace App\Repository;

use App\Config;

class UserRepository {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll(Array $input)
    {
        $query = "
            SELECT 
                id, name, email, (
                    SELECT 
                        COALESCE(SUM(amount), 0)
                    FROM 
                        drink d 
                    WHERE 
                        d.idUser=u.id AND
                        date(createdAt) = :date
                ) AS drink_count
            FROM
                users u
            ORDER BY 
                drink_count DESC
            LIMIT :limit OFFSET :offset;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'limit' => $input['limit'] ?? Config::QUERY_LIMIT,
                'offset' => $input['offset'] ?? Config::QUERY_OFFSET,
                'date' => date('Y-m-d'),
            ]);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findById($id)
    {
        $query = "
            SELECT 
                id, name, email, (
                    SELECT 
                        COALESCE(SUM(amount), 0)
                    FROM 
                        drink d 
                    WHERE 
                        d.idUser=u.id AND
                        date(createdAt) = :date
                ) AS drink_count
            FROM
                users u
            WHERE
                u.id = :id;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'id' => $id,
                'date' => date('Y-m-d'),
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function findByEmail($email)
    {
        $query = "
            SELECT 
                id, name, email, (
                    SELECT 
                        COALESCE(SUM(amount), 0)
                    FROM 
                        drink d 
                    WHERE 
                        d.idUser=u.id AND
                        date(createdAt) = :date
                ) AS drink_count
            FROM
                users u
            WHERE
                u.email = :email;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'email' => $email,
                'date' => date('Y-m-d'),
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function findByEmailSenha($email, $password)
    {
        $query = "
            SELECT 
                id, name, email, (
                    SELECT 
                        COALESCE(SUM(amount), 0)
                    FROM 
                        drink d 
                    WHERE 
                        d.idUser=u.id AND
                        date(createdAt) = :date
                ) AS drink_count
            FROM
                users u
            WHERE
                u.email = :email AND
                u.password = :password;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'email' => $email,
                'password' => $password,
                'date' => date('Y-m-d'),
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function findByToken($token)
    {
        $query = "
            SELECT 
                id, name, email, (
                    SELECT 
                        COALESCE(SUM(amount), 0)
                    FROM 
                        drink d 
                    WHERE 
                        d.idUser=u.id AND
                        date(createdAt) = :date
                ) AS drink_count
            FROM
                users u
            WHERE
                u.token = :token;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'token' => $token,
                'date' => date('Y-m-d'),
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        $query = "
            INSERT INTO users 
                (name, email, password)
            VALUES
                (:name, :email, :password);
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);
            
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $query = "
            UPDATE users
            SET 
                name = :name,
                email = :email,
                password = :password
            WHERE id = :id;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'id' => (int) $id,
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);
            
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $query = "
            DELETE FROM users
            WHERE id = :id;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
 
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}