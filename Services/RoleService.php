<?php

namespace Services;

use Traits\Connection;
use PDOException;

class RoleService {

    use Connection;

    public function getRoles() {

        $this->openConnection();

        try {

            $statement = $this->conn->query('SELECT * FROM roles');
            $roles = $statement->fetchAll();
            return $roles;

        } catch(PDOException $ex) {

            echo ($ex->getMessage());

        } finally {

            $this->closeConnection();

        }

    }

}