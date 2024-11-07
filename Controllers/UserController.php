<?php

require_once('../autoload.php');

use Services\CareerService;
use Services\RoleService;
use Models\User;

class UserController extends User{

    public function __construct() {}

    public function index() {

        $roleService = new RoleService();
        $careerService = new CareerService();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filters = [];

        if (!empty($_GET['nombre'])) {

            $filters[] = ['value' => 'searcher', 'search' => htmlspecialchars($_GET['nombre'])];

        }

        if (!empty($_GET['rol'])) {

            $filters[] = ['value' => 'rol', 'rol' => (int)$_GET['rol']];

        }

        $careers = $careerService->getCareers();
        $roles = $roleService->getRoles();

        $results = $this->getPaginated($page, $filters);

        require_once('../Views/UserTable.php');

    }


    public function create(){

        $roleService = new RoleService();
        $careerService = new CareerService();

        $roles = $roleService->getRoles();
        $careers = $careerService->getCareers();

        $title = 'Crear usuario';

        require_once('../Views/UserManager.php');

    }

    public function getUser($id){

        return $this->getOne($id);

    }

    public function update($request){

        $name = $request['fullName'];
        $document = $request['documentNumber'];
        $role = $request['role'];
        $cellphone = $request['phone'];
        $password = $request['password'];
        $id = $request['userId'];

        $idCareer = !empty($request['career']) ? $request['career'] : null;

        parent::__construct($name, $document, $role, $cellphone, $idCareer, $password);
        $this->updateUser($id);

    }

    public function deleteUser($request){

        $this->delete($request['deleteId']);

    }

    public function store($request){

        try {

            $name = $request['fullName'];
            $document = $request['documentNumber'];
            $role = $request['role'];
            $cellphone = $request['phone'];
            $password = $request['password'];
            $idCareer = !empty($request['career']) ? $request['career'] : null;

            parent::__construct($name, $document, $role, $cellphone, $idCareer, $password);
            $userId = $this->storeUser(); 

            header('Location: http://localhost/Universidad/Controllers/UserController.php?message=Usuario creado con éxito&success=1');
            exit;

        } catch (PDOException $ex) {

            header('Location: http://localhost/Universidad/Controllers/UserController.php?message=' . $ex->getMessage() . '&success=0');
            exit;

        }

    }

}

$controllerInstance = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['userId'])) {

        $result = $controllerInstance->getUser($_GET['userId']);
        echo (json_encode($result));

        if ($result->status === 200) {

            $roleService = new RoleService();
            $roles = $roleService->getRoles();

            $careerService = new CareerService();
            $careers = $careerService->getCareers();

            $userData = $result->user;
            $title = 'Editar usuario';

            require_once('../Views/UserManager.php');

        }
    } else if (isset($_GET['create'])) {

        $controllerInstance->create();

    } else if (isset($_GET['deleteId'])) {

        $controllerInstance->deleteUser($_GET);

    } else {

        $controllerInstance->index();

    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['userId'])) {

        $controllerInstance->update($_POST);
        return;

    }

    $controllerInstance->store($_POST);

}