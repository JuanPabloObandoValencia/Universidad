<?php
namespace Services;

use Traits\Connection;
use PDOException;

class CareerService{

    use Connection;

    public function getCareers() {

        $this->openConnection();

        try {

            $statement = $this->conn->query('SELECT * FROM Carreras');
            $careers = $statement->fetchAll();
            return $careers;

        } catch(PDOException $ex) {

            echo ($ex->getMessage());

        } finally {

            $this->closeConnection();

        }

    }

}