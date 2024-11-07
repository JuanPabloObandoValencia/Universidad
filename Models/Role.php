<?php

namespace Models;

use Traits\Connection;
use PDOException;

Class Role {

    private $nombre;
    private $id;

    use Connection;

    protected function __construct($nombre, $id=null) {

        $this->nombre= $nombre;
        $this->id= $id;

    }

    protected function storeRole() {

        $this->openConnection();

        try{

            $query = "INSERT INTO Roles (nombre) VALUES (:nombre)";
            $statement = $this->conn->prepare($query);
            $statement->execute(
                [
                    ':nombre' => $this->nombre,
                ]
                );

                header('Location: http://localhost/Universidad/Controllers/RoleController.php?message=Rol registrado con éxito&success=1');

        }catch(PDOException $ex){

            header('Location: http://localhost/Universidad/Controllers/RoleController.php?message=' . $ex->getMessage() . '&success=0');

        }finally{

            $this->closeConnection();

        }

    }

    protected function updateRole() {

        $this->openConnection();

        try {

            $query = "UPDATE Roles SET nombre = :name WHERE id = :id";
            $statement = $this->conn->prepare($query);
            $statement->execute([
                ':name' => $this->nombre,
                ':id' => $this->id,
            ]);

            header('Location: http://localhost/Universidad/Controllers/RoleController.php?message=Carrera actualizada con éxito&success=1');

        } catch (PDOException $ex) {

            header('Location: http://localhost/Universidad/Controllers/RoleController.php?message=' . $ex->getMessage() . '&success=0');

        } finally {

            $this->closeConnection();

        }

    }

    protected function delete($id) {

        $this->openConnection();

        try {

            $query = "DELETE FROM Roles WHERE id = :id";
            $statement = $this->conn->prepare($query);
            $statement->execute(
                    [
                        ':id' => $id,
                    ]
                );

            if ($statement->rowCount()) {

                header('Location: http://localhost/Universidad/Controllers/RoleController.php?message=Rol eliminado con éxito&success=1');
                return;

            }

            header('Location: http://localhost/Universidad/ControllersRoleController.php?message=Rol no encontrado&success=1');

        } catch(PDOException $ex) {

            header('Location: http://localhost/Universidad/Controllers/RoleController.php?message=' . $ex->getMessage() . '&success=0');

        } finally {

            $this->closeConnection();

        }

    }

    protected function getOne($id) {

        $this->openConnection();

        try{

            $query = "SELECT id, nombre FROM Roles WHERE id = :id";
            $statement = $this->conn->prepare($query);
            $statement->execute(['id' => $id]);

            if ($statement->rowCount()) {

                return (object) [
                    'status' => 200,
                    'success' => true,
                    'role' => $statement->fetch(),
                ];
            }

        }catch(PDOException $ex){

            return (object) [
                'status' => 400,
                'error' => $ex->getMessage(),
            ];

        }finally{

            $this->closeConnection();

        }

    }

    protected function getPaginated($page = 1, $filters = []) {

        $resultsPerPage = 3;

        $query = "SELECT id, nombre FROM Roles";
        $queryCount = "SELECT COUNT(id) AS total_roles FROM Roles";

        $initialRegister = ($page - 1) * $resultsPerPage;
        $filterValues = [];

        $filtersCount = count($filters);

        if ($filtersCount) {

            $query .= ' WHERE';
            $queryCount .= ' WHERE';

            foreach ($filters as $key => $filter) {

                switch ($filter['value']) {

                    case 'searcher':
                        
                        $text = ' nombre LIKE :search';
                        $query .= $text;
                        $filterValues[':search'] = '%' . $filter['search'] . '%'; 
                        break;

                }

                if ($key < $filtersCount - 1) {

                    $query .= ' AND';
                    $queryCount .= ' AND';

                }

            }

        }

        $query .= " ORDER BY id ASC LIMIT $initialRegister, $resultsPerPage";

        try {

            $this->openConnection();

            $statement = $this->conn->prepare($query);
            $statement->execute($filterValues);
            $paginatedRoles = $statement->fetchAll(); 
            $statement->closeCursor();

            $statement = $this->conn->prepare($queryCount);
            $statement->execute($filterValues);
            $totalRoles = $statement->fetch()->total_roles;

            return (object) [
                'status' => 200,
                'registers' => $paginatedRoles,
                'totalPages' => ceil($totalRoles / $resultsPerPage),
                'resultsPerPage' => $resultsPerPage,
                'currentPage' => $page,
            ];

        } catch (PDOException $ex) {

            return (object) [
                'status' => 400,
                'error' => $ex->getMessage(),
            ];

        } finally {

            $this->closeConnection();

        }

    }

}