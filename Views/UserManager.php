<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($title) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1 id="form-title"><?php echo ($title) ?></h1>
        <form id="user-form" action="../Controllers/UserController.php" method="POST">
            <div class="mb-3">
                <label for="fullName" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Ingrese el nombre completo" value="<?php echo ((isset($userData) && $result->status) ? $userData->nombreCompleto : '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="cedula" class="form-label">Cédula</label>
                <input type="text" class="form-control" id="cedula" name="documentNumber" placeholder="Ingrese la cédula" value="<?php echo ((isset($userData) && $result->status) ? $userData->cedula : '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Celular</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Ingrese el número de celular" value="<?php echo ((isset($userData) && $result->status) ? $userData->celular : '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-select" id="role" name="role" required onchange="toggleCareerSelect()">
                    <option value="">Seleccione el rol</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role->id; ?>" <?php echo (isset($userData->role) && $userData->role == $role->id) ? 'selected' : ''; ?>>
                            <?php echo $role->nombre; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="career-container" style="display: <?php echo (isset($userData->role) && $userData->role === 'Estudiante') ? 'block' : 'none'; ?>;">
                <div class="mb-3">
                    <label for="career" class="form-label">Carrera</label>
                    <select class="form-select" id="career" name="career">
                        <option value="">Seleccione la carrera</option>
                        <?php foreach ($careers as $career): ?>
                            <option value="<?php echo $career->id; ?>"
                                <?php echo (isset($userData->idCarrera) && $userData->idCarrera == $career->id) ? 'selected' : ''; ?>>
                                <?php echo $career->nombre; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese la contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary"><?php echo ((isset($userData) && $result->status) ? 'Editar usuario' : 'Crear usuario') ?></button>

            <?php if ($title === 'Editar usuario'): ?>
                <input type="hidden" name='userId' value='<?php echo (isset($userData) && $result->status ? $userData->id : '') ?>'>
            <?php endif ?>
        </form>
    </div>
    <script src="../Views/Assets/js/main.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>