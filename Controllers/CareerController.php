<?php

require_once('../autoload.php');

use Services\CareerService;

use Models\Career;


class CareerController extends Career {

    public function __construct(){}

    public function index(){

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $results = $this->getPaginated($page);

        json_encode($results);
        require_once('../Views/CareersTable.php');

    }

    public function create() {

        $title = 'Agregar Carrera';

        require_once('../Views/CareerManager.php');
    }

    public function getCareer($id) {

        return $this->getOne($id);

    }

    public function update($request){

        $name= $request['careerName'];
        $id = $request['careerId'];

        parent::__construct($id, $name);
        $this->updateCareer();

    }

    public function deleteCareer($request){

        $this->delete($request['deleteId']);
    }

    public function store($request) {

        $nombre = $request['careerName'];
        $id = $request['careerId'];

        parent::__construct($nombre, $id); 

        $this->storeCareer();

    }

}

$controllerInstance = new CareerController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['careerId'])) {

        $result = $controllerInstance->getCareer($_GET['careerId']);

        echo(json_encode($result));

        if ($result->status === 200) {

            $careerData = $result->career;
            $title = 'Editar Carrera';

            require_once('../Views/CareerManager.php');

        }

    } else if (isset($_GET['create'])) {

        $controllerInstance->create();

    } else if(isset($_GET['deleteId'])){

        $controllerInstance->deleteCareer($_GET);

    }else {

        $controllerInstance->index();

    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['careerId'])) {

        echo(json_encode($_POST));
        $controllerInstance->update($_POST);

        return;

    }

    $controllerInstance->store($_POST);

}