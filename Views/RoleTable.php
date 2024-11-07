<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoleTable</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2>Lista de Roles</h2>
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" id="search-input" placeholder="Buscar rol por nombre">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="search-button">Buscar</button>
            </div>
            <div class="col-md-2 d-flex">
                <a href="../index.php" class="me-2">
                    <button class="btn btn-secondary">Volver a Menú</button>
                </a>
            </div>
        </div>

        <a href="../Controllers/RoleController.php?create" class="btn btn-success mb-3">Crear Rol</a>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert <?= ($_GET['success'] == 1) ? 'alert-success' : 'alert-danger'; ?> mt-4" role="alert">
                <?= htmlspecialchars($_GET['message'] ?? '') ?>
            </div>
        <?php endif; ?>

        <?php if (isset($results->registers) && !empty($results->registers)): ?>
            <table class="table table-bordered my-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    <?php foreach ($results->registers as $role): ?>
                        <tr>
                            <td><?= htmlspecialchars($role->id ?? '') ?></td>
                            <td><?= htmlspecialchars($role->nombre ?? '') ?></td>
                            <td>
                                <a href="http://localhost/Universidad/Controllers/RoleController.php?roleId=<?= $role->id ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="http://localhost/Universidad/Controllers/RoleController.php?deleteId=<?= $role->id ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning mt-4" role="alert">
                No hay roles registrados.
            </div>
        <?php endif; ?>

        <?php if (!empty($results->registers)): ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $results->currentPage == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="http://localhost/Universidad/Controllers/RoleController.php?page=<?= max(1, $results->currentPage - 1) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $results->totalPages; $i++): ?>
                        <li class="page-item <?= $i == $results->currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="http://localhost/Universidad/Controllers/RoleController.php?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $results->currentPage == $results->totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="http://localhost/Universidad/Controllers/RoleController.php?page=<?= min($results->totalPages, $results->currentPage + 1) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>
