<?php

namespace Traits;

use PDO;
use PDOException;

trait Connection {

    private $dsn;
    private $user;
    private $password;
    protected $conn;

    protected function openConnection() {

        try {

            $this->dsn = "mysql:host=localhost;dbname=Universidad";
            $this->user = 'root';
            $this->password = '';

            $this->conn = new PDO($this->dsn, $this->user, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        } catch(PDOException $ex) {

            echo($ex->getMessage());

        }

    }

    protected function closeConnection() {

        $this->conn = null;

    }

}