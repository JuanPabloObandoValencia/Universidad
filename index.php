<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones del Sistema</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sistema de Información Académico</h2>

        <div class="row">

            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Carreras</h5>
                        <p class="card-text">Gestión de las carreras.</p>
                        <a href="http://localhost/Universidad/Controllers/CareerController.php" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Estudiantes</h5>
                        <p class="card-text">Estudiantes por Carreras.</p>
                        <a href="http://localhost/Universidad/Controllers/StudentController.php" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Gestión de los usuarios.</p>
                        <a href="http://localhost/Universidad/Controllers/UserController.php" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Roles</h5>
                        <p class="card-text">Gestión de los roles.</p>
                        <a href="http://localhost/Universidad/Controllers/RoleController.php" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>