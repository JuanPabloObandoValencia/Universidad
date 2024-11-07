<?php

namespace Models;

use Traits\Connection;
use PDOException;

class Student
{
    private $id;
    private $idUsuario;
    private $idCarrera;

    use Connection;

    public function __construct($idUsuario, $idCarrera, $id = null){

        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idCarrera = $idCarrera;

    }

    public function storeStudent() {

        $this->openConnection();

        try {

            $query = "INSERT INTO Estudiantes (idUsuario, idCarrera) VALUES (:idUsuario, :idCarrera)";
            $statement = $this->conn->prepare($query);
            $statement->execute([
                ':idUsuario' => $this->idUsuario,
                ':idCarrera' => $this->idCarrera
            ]);

        } catch (PDOException $ex) {

            throw new PDOException('Error al insertar el estudiante: ' . $ex->getMessage());

        } finally {

            $this->closeConnection();

        }

    }

    public function updateStudent() {

        $this->openConnection();

        try {

            $query = "UPDATE Estudiantes SET idCarrera = :idCarrera WHERE idUsuario = :idUsuario";
            $statement = $this->conn->prepare($query);
            $params = [
                ':idCarrera' => $this->idCarrera,
                ':idUsuario' => $this->idUsuario,
            ];

            $statement->execute($params);

        } catch (PDOException $ex) {

            throw new PDOException('Error al actualizar el estudiante: ' . $ex->getMessage());

        } finally {

            $this->closeConnection();

        }

    }

    public function deleteStudent($id) {

        $this->openConnection();

        try {

            $query = "DELETE FROM Estudiantes WHERE idUsuario = :id";
            $statement = $this->conn->prepare($query);
            $statement->execute([':id' => $id]);

            if ($statement->rowCount() > 0) {

                return
                header('Location: http://localhost/Universidad/Controllers/UserController.php?message=Estudiante eliminado con éxito&success=1');
                exit;

            } else {

                return;
                header('Location: http://localhost/Universidad/Controllers/UserController.php?message=Estudiante no encontrado&success=0');
                exit;

            }

        } catch (PDOException $ex) {

            header('Location: http://localhost/Universidad/Controllers/StudentController.php?message=' . urlencode($ex->getMessage()) . '&success=0');
            exit;

        } finally {

            $this->closeConnection();

        }

    }

    public function getOne($id){

        $this->openConnection();

        try {

            $query = "SELECT id, idUsuario, idCarrera FROM Estudiantes WHERE id = :id";
            $statement = $this->conn->prepare($query);
            $statement->execute([':id' => $id]);

            if ($statement->rowCount()) {

                return (object) [
                    'status' => 200,
                    'success' => true,
                    'student' => $statement->fetch(),
                ];

            }

        } catch (PDOException $ex) {

            return (object) [
                'status' => 400,
                'error' => $ex->getMessage(),
            ];

        } finally {

            $this->closeConnection();

        }

    }

    public function getPaginated($page = 1, $filters = []) {

        $resultsPerPage = 10;
        $initialRegister = ($page - 1) * $resultsPerPage;

        $query = "SELECT U.id AS idUsuario, E.id AS estudianteId, U.nombreCompleto, U.cedula, C.nombre AS carreraNombre 
                    FROM Estudiantes E 
                    INNER JOIN Usuarios U ON U.id = E.idUsuario 
                    INNER JOIN Carreras C ON E.idCarrera = C.id";

        $queryCount = "SELECT COUNT(E.id) AS total_students FROM Estudiantes E";

        $whereClauses = [];
        $filterValues = [];

        foreach ($filters as $filter) {

            if ($filter['value'] === 'searcher') {

                $whereClauses[] = "U.nombreCompleto LIKE :search OR U.cedula LIKE :search";
                $filterValues[':search'] = '%' . $filter['search'] . '%';

            }

            if ($filter['value'] === 'career') {
                $whereClauses[] = "E.idCarrera = :career";
                $filterValues[':career'] = $filter['career'];

            }

            if ($filter['value'] === 'role') {

                $whereClauses[] = "U.roleId = :role";
                $filterValues[':role'] = $filter['role'];

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
            $paginatedStudents = $statement->fetchAll();
            $statement->closeCursor();

            $statement = $this->conn->prepare($queryCount);
            $statement->execute($filterValues);
            $totalStudents = $statement->fetch()->total_students;

            return (object) [
                'status' => 200,
                'registers' => $paginatedStudents,
                'totalPages' => ceil($totalStudents / $resultsPerPage),
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

