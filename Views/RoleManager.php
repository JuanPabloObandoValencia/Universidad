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
        <form id="user-form" action="../Controllers/RoleController.php" method="POST">
            <!-- <input type="hidden" name="roleId" value="<?php echo ($roleData->id ?? ''); ?>"> -->
            <div class="mb-3">
                <label for="roleName" class="form-label">Nombre Rol</label>
                <input type="text" class="form-control" id="roleName" name="roleName" placeholder="Ingrese el nombre de el rol" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Rol</button>
            <?php if ($title === 'Editar Rol'): ?>

                <input type="hidden" name='roleId' value='<?php echo (isset($roleData) && $result->status ? $roleData->id : '') ?>'>

            <?php endif ?>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>