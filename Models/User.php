<?php

namespace Models;

use Traits\Connection;
use PDOException;

class User {

    private $id;
    private $completeName;
    private $identityDocument;
    private $role;
    private $celNumber;
    private $password;
    private $idCareer;

    use Connection;

    protected function __construct($name, $identityDocument, $role, $number, $idCareer = null, $password = null, $id = null) {

        $this->completeName = $name;
        $this->identityDocument = $identityDocument;
        $this->role = $role;
        $this->celNumber = $number;
        $this->password = $password;
        $this->idCareer = $idCareer;
        $this->id = $id;

    }

    public function storeUser() {

        $this->openConnection();
    
        try {

            $checkQuery = "SELECT idCarrera FROM Usuarios WHERE cedula = :document";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([':document' => $this->identityDocument]);

            if ($checkStmt->fetchColumn()) {

                header('Location: http://localhost/Universidad/Controllers/UserController.php?message=El usuario ya está registrado en otra carrera&success=0');
                exit;

            }

            $query = "INSERT INTO Usuarios (nombreCompleto, cedula, roleId, celular, clave, idCarrera) VALUES (:name, :document, :role, :cel, :password, :idCareer)";
            $statement = $this->conn->prepare($query);

            $params = [
                ':name' => $this->completeName,
                ':document' => $this->identityDocument,
                ':role' => $this->role,
                ':cel' => $this->celNumber,
                ':password' => password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 12]),
                ':idCareer' => $this->idCareer,
            ];

            $statement->execute($params);

            $userId = $this->conn->lastInsertId();

            $roleQuery = "SELECT nombre FROM Roles WHERE id = :roleId";
            $roleStmt = $this->conn->prepare($roleQuery);
            $roleStmt->execute([':roleId' => $this->role]);
            $roleName = $roleStmt->fetchColumn();

            if ($roleName === "Estudiante" && $this->idCareer !== null) {

                $student = new Student($userId, $this->idCareer);
                $student->storeStudent();

            }

            header('Location: http://localhost/Universidad/Controllers/UserController.php?message=Usuario insertado con éxito&success=1');
            exit;
            return $userId;

        } catch (PDOException $ex) {

            header('Location: http://localhost/Universidad/Controllers/UserController.php?message=' . $ex->getMessage() . '&success=0');
            exit;

        } finally {

            $this->closeConnection();

        }

    }

    public function updateUser($userId) {

        $this->openConnection();

        try {

            $query = "UPDATE Usuarios SET nombreCompleto = :name, cedula = :document, roleId = :role, celular = :cel, idCarrera = :idCareer WHERE id = :userId";
            $statement = $this->conn->prepare($query);

            $params = [
                ':name' => $this->completeName,
                ':document' => $this->identityDocument,
                ':role' => $this->role,
                ':cel' => $this->celNumber,
                ':idCareer' => $this->idCareer,
                ':userId' => $userId,
            ];

            $statement->execute($params);
            $roleQuery = "SELECT nombre FROM Roles WHERE id = :roleId";
            $roleStmt = $this->conn->prepare($roleQuery);
            $roleStmt->execute([':roleId' => $this->role]);
            $roleName = $roleStmt->fetchColumn();

            if ($roleName == "Estudiante" && isset($this->idCareer)) {

                $student = new Student($userId, $this->idCareer);
                $student->updateStudent();

            }

            header('Location: http://localhost/Universidad/Controllers/UserController.php?message=Usuario actualizado con éxito&success=1');
            exit;

        } catch (PDOException $ex) {

            header('Location: http://localhost/Universidad/Controllers/UserController.php?message=' . $ex->getMessage() . '&success=0');
            exit;

        } finally {

            $this->closeConnection();

        }

    }

    protected function delete($id) {

        $this->openConnection();

        try {

            $roleQuery = "SELECT roleId FROM Usuarios WHERE id = :id";
            $roleStmt = $this->conn->prepare($roleQuery);
            $roleStmt->execute([':id' => $id]);
            $roleId = $roleStmt->fetchColumn();

            $roleNameQuery = "SELECT nombre FROM Roles WHERE id = :roleId";
            $roleNameStmt = $this->conn->prepare($roleNameQuery);
            $roleNameStmt->execute([':roleId' => $roleId]);
            $roleName = $roleNameStmt->fetchColumn();

            if ($roleName === "Estudiante") {
                $student = new Student($id, null); 
                $student->deleteStudent($id); 
            }
            $query = "DELETE FROM Usuarios WHERE id = :id";
            $statement = $this->conn->prepare($query);
            $statement->execute([':id' => $id]);

            if ($statement->rowCount() > 0) {

                header('Location: http://localhost/Universidad/Controllers/UserController.php?message=Usuario eliminado con éxito&success=1');
                exit;

            } else {

                header('Location: http://localhost/Universidad/Controllers/UserController.php?message=No se pudo eliminar al usuario&success=0');
                exit;

            }

        } catch (PDOException $ex) {

            header('Location: http://localhost/Universidad/Controllers/UserController.php?message=' . urlencode($ex->getMessage()) . '&success=0');
            exit;

        } finally {

            $this->closeConnection();

        }

    }

    protected function getOne($id) {

        $this->openConnection();

        try {

            $query = "SELECT id, nombreCompleto, cedula, roleId, celular FROM Usuarios WHERE id = :id";
            $statement = $this->conn->prepare($query);
            $statement->execute([':id' => $id]);

            if ($statement->rowCount()) {

                return (object) [
                    'status' => 200,
                    'success' => true,
                    'user' => $statement->fetch(),
                ];
            }

            return (object) [
                'status' => 200,
                'success' => false,
            ];

        } catch(PDOException $ex) {

            return (object) [
                'status' => 400,
                'error' => $ex->getMessage(),
            ];

        } finally {

            $this->closeConnection();

        }

    }

    protected function getPaginated($page = 1, $filters = []) {

        $resultsPerPage = 10;
        $initialRegister = ($page - 1) * $resultsPerPage;

        $query = "SELECT id, nombreCompleto, cedula, roleId, idCarrera, celular FROM Usuarios U";
        $queryCount = "SELECT COUNT(U.id) AS total_users FROM Usuarios U";

        $whereClauses = [];
        $filterValues = [];

        foreach ($filters as $filter) {

            if ($filter['value'] === 'searcher') {

                $whereClauses[] = "U.nombreCompleto LIKE :search OR U.cedula LIKE :search";
                $filterValues[':search'] = '%' . $filter['search'] . '%';

            }
            if ($filter['value'] === 'rol') {

                $whereClauses[] = "U.roleId = :rol";
                $filterValues[':rol'] = $filter['rol'];

            }

        }

        if (!empty($whereClauses)) {

            $query .= ' WHERE ' . implode(' AND ', $whereClauses);
            $queryCount .= ' WHERE ' . implode(' AND ', $whereClauses);

        }

        $query .= " ORDER BY U.id ASC LIMIT $initialRegister, $resultsPerPage";

        try {

            $this->openConnection();

            $statement = $this->conn->prepare($query);
            $statement->execute($filterValues);
            $paginatedUsers = $statement->fetchAll(); 
            $statement->closeCursor();

            $statement = $this->conn->prepare($queryCount);
            $statement->execute($filterValues);
            $totalUsers = $statement->fetch()->total_users;

            return (object) [
                'status' => 200,
                'registers' => $paginatedUsers,
                'totalPages' => ceil($totalUsers / $resultsPerPage),
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
