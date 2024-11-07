<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2>Lista de Usuarios</h2>

        <form method="GET" action="">
            <div class="row mb-3 align-items-end">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="search-input" name="nombre" placeholder="Buscar usuario por nombre o cédula" value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="rol" id="role-filter">
                        <option value="">Seleccione un rol</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role->id ?>" <?= (isset($_GET['rol']) && $_GET['rol'] == $role->id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role->nombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                    <a href="../index.php" class="btn btn-secondary">Volver a Menú</a>
                </div>
            </div>
        </form>

        <a href="./UserController.php?create=true" class="btn btn-success mb-3">Crear usuario</a>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success mt-4" role="alert">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif ?>

        <?php if (isset($results->registers) && !empty($results->registers)): ?>
            <table class="table table-bordered my-3">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Cédula</th>
                        <th>Rol</th>
                        <th>Carrera</th>
                        <th>Celular</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    <?php foreach ($results->registers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user->nombreCompleto) ?></td>
                            <td><?= htmlspecialchars($user->cedula) ?></td>
                            <td>
                                <?php
                                $roleName = '';
                                foreach ($roles as $role) {
                                    if ($user->roleId === $role->id) {
                                        $roleName = htmlspecialchars($role->nombre);
                                        break;
                                    }
                                }
                                echo $roleName;
                                ?>
                            </td>
                            <td>
                                <?php
                                if (isset($user->idCarrera) && $user->idCarrera !== null) {
                                    $careerName = 'No es estudiante';
                                    foreach ($careers as $career) {
                                        if ($user->idCarrera === $career->id) {
                                            $careerName = htmlspecialchars($career->nombre);
                                            break;
                                        }
                                    }
                                    echo $careerName;
                                } else {
                                    echo "No es estudiante";
                                }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($user->celular) ?></td>
                            <td>
                                <a href="http://localhost/Universidad/Controllers/UserController.php?userId=<?= $user->id ?>" class="btn btn-warning btn-sm edit-user">Editar</a>
                                <a href="http://localhost/Universidad/Controllers/UserController.php?deleteId=<?= $user->id ?>" class="btn btn-danger btn-sm delete-user">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item <?= $results->currentPage == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= max(1, $results->currentPage - 1) ?>&nombre=<?= htmlspecialchars($_GET['nombre'] ?? '') ?>&rol=<?= htmlspecialchars($_GET['rol'] ?? '') ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $results->totalPages; $i++): ?>
                        <li class="page-item <?= $i == $results->currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&nombre=<?= htmlspecialchars($_GET['nombre'] ?? '') ?>&rol=<?= htmlspecialchars($_GET['rol'] ?? '') ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $results->currentPage == $results->totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= min($results->totalPages, $results->currentPage + 1) ?>&nombre=<?= htmlspecialchars($_GET['nombre'] ?? '') ?>&rol=<?= htmlspecialchars($_GET['rol'] ?? '') ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        <?php else: ?>
            <div class="alert alert-warning mt-4" role="alert">
                No hay usuarios registrados.
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
