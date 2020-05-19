<?php
namespace App\Repository;

class DrinkRepository {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insert(Array $input)
    {
        $query = "
            INSERT INTO drink 
                (idUser, amount, createdAt)
            VALUES
                (:idUser, :amount, datetime('now'));
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'idUser' => $input['idUser'],
                'amount' => $input['drink_count'],
            ]);
            
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $query = "
            DELETE FROM drink
            WHERE idUser = :id;
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
