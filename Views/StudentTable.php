<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentsTable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2>Lista de Estudiantes</h2>
        <form method="GET" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nombre" placeholder="Buscar por nombre" value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="carrera">
                        <option value="">Seleccione una carrera</option>
                        <?php foreach ($careers as $career): ?>
                            <option value="<?= $career->id ?>" <?= (isset($_GET['carrera']) && $_GET['carrera'] == $career->id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($career->nombre) ?>
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

        <?php if (isset($result->registers) && !empty($result->registers)): ?>
            <table class="table table-bordered my-3">
                <thead>
                    <tr>
                        <th>ID Usuario</th>
                        <th>ID Estudiante</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result->registers as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student->idUsuario) ?></td>
                            <td><?= htmlspecialchars($student->estudianteId) ?></td>
                            <td><?= htmlspecialchars($student->cedula) ?></td>
                            <td><?= htmlspecialchars($student->nombreCompleto) ?></td>
                            <td><?= htmlspecialchars($student->carreraNombre) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $result->totalPages; $i++): ?>
                        <li class="page-item <?= $i == $result->currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&nombre=<?= urlencode($_GET['nombre'] ?? '') ?>&carrera=<?= urlencode($_GET['carrera'] ?? '') ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php else: ?>
            <div class="alert alert-warning mt-4" role="alert">
                No hay estudiantes registrados.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
