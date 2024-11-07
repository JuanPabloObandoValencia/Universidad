<?php

require_once('../autoload.php');

use Services\CareerService;
use Models\Student;

class StudentController extends Student{

    public function __construct() {}

    public function index() {

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filters = [];

        $careerService = new CareerService();
        $careers = $careerService->getCareers();

        if (!empty($_GET['nombre'])) {

            $filters[] = ['value' => 'searcher', 'search' => $_GET['nombre']];

        }

        if (!empty($_GET['carrera'])) {

            $filters[] = ['value' => 'career', 'career' => $_GET['carrera']];

        }        

        $result = $this->getPaginated($page, $filters);


        require_once('../Views/StudentTable.php');

    }

    public function create() {

        $title = 'Crear Estudiante';

        require_once('../Views/UserManager.php');

    }

    public function getStudent($id) {

        return $this->getOne($id);

    }

    public function deleteStudent($id) {

        $this->deleteStudent($id);

        header('Location: http://localhost/Universidad/Controllers/StudentController.php?message=Estudiante eliminado con éxito&success=1');
        exit;

    }

    public function store($request) {

        try {

            if (empty($request['careerId'])) {

                throw new Exception('El ID de carrera es requerido');

            }

            $student = new Student($request['userId'], $request['careerId']);
            $student->storeStudent();

            header('Location: http://localhost/Universidad/Controllers/StudentController.php?message=Estudiante registrado con éxito&success=1');
            exit;

        } catch (Exception $ex) {

            header('Location: http://localhost/Universidad/Controllers/StudentController.php?message=' . $ex->getMessage() . '&success=0');
            exit;

        }

    }

    public function update($request) {

        $id = $request['userId'];
        $idCareer = !empty($request['career']) ? $request['career'] : null;
        parent::__construct(null, null, null, null, $idCareer, null);
        $this->updateStudent($id);

        header('Location: http://localhost/Universidad/Controllers/StudentController.php?message=Estudiante actualizado con éxito&success=1');
        exit;

    }

}

$studentController = new StudentController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['action'])) {

        switch ($_GET['action']) {

            case 'create':
                $studentController->create();
                break;

            case 'deleteId':
                if (isset($_GET['id'])) {

                    $studentController->deleteStudent($_GET['id']);

                }
                break;

            default:
                $studentController->index();

        }

    } else {

        $studentController->index();

    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['userId'])) {

        $studentController->update($_POST);
        return;

    }

    $studentController->store($_POST);

}


