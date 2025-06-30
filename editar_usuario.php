<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1); 

include 'config.php';

$usuario_id = $_GET['id_usuario'] ?? null;
$usuario = null;

if ($usuario_id) {
    $sql = "SELECT id, nombre_usuario, email, rol, estado FROM usuarios WHERE id = '" . $conn->real_escape_string($usuario_id) . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        header("Location: gestion_usuarios.php?status=error&message=Usuario no encontrado.");
        exit();
    }
} else {
    header("Location: gestion_usuarios.php?status=error&message=ID de usuario no especificado.");
    exit();
}

// Lógica para guardar los cambios del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_usuario']) && isset($_POST['email']) && isset($_POST['rol'])) {
    $id_usuario_post = $conn->real_escape_string($_POST['id_usuario']);
    $nuevo_email = $conn->real_escape_string($_POST['email']);
    $nuevo_rol = $conn->real_escape_string($_POST['rol']);

    // Obtener los datos actuales del usuario para el log de auditoría
    $old_data_sql = "SELECT email, rol FROM usuarios WHERE id = '$id_usuario_post'";
    $old_data_result = $conn->query($old_data_sql);
    $old_data = $old_data_result->fetch_assoc();

    $update_sql = "UPDATE usuarios SET email = '$nuevo_email', rol = '$nuevo_rol' WHERE id = '$id_usuario_post'";

    if ($conn->query($update_sql) === TRUE) {
        // Registrar en el log de auditoría
        $descripcion_cambio = "Usuario ID: " . $id_usuario_post . " - ";
        if ($old_data['email'] != $nuevo_email) {
            $descripcion_cambio .= "Email cambiado de '" . $old_data['email'] . "' a '" . $nuevo_email . "'. ";
        }
        if ($old_data['rol'] != $nuevo_rol) {
            $descripcion_cambio .= "Rol cambiado de '" . $old_data['rol'] . "' a '" . $nuevo_rol . "'. ";
        }
        $admin_id_auditoria = 1; // ID de un admin de prueba, o NULL si aún no tienes usuarios en BD.

        $log_sql = "INSERT INTO log_auditoria (usuario_id, accion, tabla_afectada, registro_id_afectado, descripcion_cambio) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($log_sql);
        $accion = 'editar_usuario';
        $tabla_afectada = 'usuarios';
        $stmt->bind_param("isiss", $admin_id_auditoria, $accion, $tabla_afectada, $id_usuario_post, $descripcion_cambio);
        $stmt->execute();
        $stmt->close();

        header("Location: gestion_usuarios.php?status=success&action=actualizado");
        exit();
    } else {
        header("Location: editar_usuario.php?id_usuario=" . $id_usuario_post . "&status=error&message=" . urlencode($conn->error));
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - AquaLerta Admin</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilo.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            margin-left: 10px;
            text-decoration: none; /* Para que parezca un botón */
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Editar Usuario: <?php echo htmlspecialchars($usuario["nombre_usuario"]); ?></h1>

        <?php
        if (isset($_GET['status']) && $_GET['status'] == 'error') {
            echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($_GET['message']) . '</div>';
        }
        ?>

        <form action="editar_usuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario["id"]); ?>">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario (no editable):</label>
                <input type="text" id="nombre_usuario" value="<?php echo htmlspecialchars($usuario["nombre_usuario"]); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario["email"]); ?>">
            </div>
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol">
                    <option value="admin" <?php echo ($usuario["rol"] == 'admin' ? 'selected' : ''); ?>>Administrador</option>
                    <option value="rescatista" <?php echo ($usuario["rol"] == 'rescatista' ? 'selected' : ''); ?>>Rescatista</option>
                    <option value="autoridad" <?php echo ($usuario["rol"] == 'autoridad' ? 'selected' : ''); ?>>Autoridad</option>
                    <option value="comunidad" <?php echo ($usuario["rol"] == 'comunidad' ? 'selected' : ''); ?>>Comunidad</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">Guardar Cambios</button>
            <a href="gestion_usuarios.php" class="btn-back">Volver a Gestión de Usuarios</a>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>