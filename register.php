<?php include 'config.php'; include 'funciones.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<title>Registro</title>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
    <h2>Registrarse</h2>

    <form method="POST" action="register.php">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apellido</label>
            <input type="text" name="apellido" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control">
        </div>
        <div class="mb-3">
            <label>Barrio</label>
            <select name="id_barrio" class="form-control" required>
                <option value="">Seleccione</option>
                <?php
                $barrios = $pdo->query("SELECT id_barrio, barrio FROM barrios LIMIT 10");
                while ($b = $barrios->fetch()) {
                    echo "<option value='{$b['id_barrio']}'>{$b['barrio']}</option>";
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="latitud" id="latitud">
        <input type="hidden" name="longitud" id="longitud">
        <button type="submit" name="registrar" class="btn btn-primary">Registrarse</button>
    </form>
    </div>
    
    <script>
    navigator.geolocation.getCurrentPosition(function(pos) {
        document.getElementById('latitud').value = pos.coords.latitude;
        document.getElementById('longitud').value = pos.coords.longitude;
    });
    </script>
    </body>
    </html>
    <?php
    if (isset($_POST['registrar'])) {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password, telefono, id_barrio, fecha_registro, tipo_usuario)
                            VALUES (?, ?, ?, ?, ?, ?, NOW(), 'ciudadano')");
        $stmt->execute([
            $_POST['nombre'], $_POST['apellido'], $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['telefono'], $_POST['id_barrio']
        ]);
        echo "<div class='alert alert-success'>Usuario registrado exitosamente</div>";
    }
    ?>