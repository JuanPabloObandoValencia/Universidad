<?php

require_once('../autoload.php');

use Services\RoleService;

use Models\Role;


class RoleController extends Role {

    public function __construct(){}

    public function index(){

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $results = $this->getPaginated($page);

        require_once('../Views/RoleTable.php');

    }

    public function create() {

        $title = 'Agregar Rol';

        require_once('../Views/RoleManager.php');
    }

    public function getRole($id) {

        return $this->getOne($id);

    }

    public function update($request){

        $name= $request['roleName'];
        $id = $request['roleId'];

        parent::__construct($id, $name);
        $this->updateRole();

    }

    public function deleteRole($request){

        $this->delete($request['deleteId']);

    }

    public function store($request) {
        $nombre = $request['roleName'];
        $id = $request['roleId'];

        parent::__construct($nombre, $id); 

        $this->storeRole();

    }

}

$controllerInstance = new RoleController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['roleId'])) {

        $result = $controllerInstance->getRole($_GET['roleId']);

        if ($result->status === 200) {

            $roleData = $result->role;
            $title = 'Editar Rol';

            require_once('../Views/RoleManager.php');

        }

    } else if (isset($_GET['create'])) {

        $controllerInstance->create();

    } else if(isset($_GET['deleteId'])){

        $controllerInstance->deleteRole($_GET);

    }else {

        $controllerInstance->index();

    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['roleId'])) {

        $controllerInstance->update($_POST);

        return;

    }
    var_dump($_POST);
    $controllerInstance->store($_POST);

}