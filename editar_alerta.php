<?php
include 'config.php'; 

$alert_id = isset($_GET['id_alerta']) ? intval($_GET['id_alerta']) : 0;
$alerta = null;

if ($alert_id > 0) {
    // Obtener los datos de la alerta específica
    $stmt = $conn->prepare("SELECT id, descripcion, latitud, longitud, estado, nivel_riesgo, zona_geografica FROM alertas WHERE id = ?");
    $stmt->bind_param("i", $alert_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $alerta = $result->fetch_assoc();
    }
    $stmt->close();
}

// Si la alerta no existe o no se proporcionó ID, redirigir al panel de administración
if ($alerta === null) {
    header("Location: admin.php?status=error&message=" . urlencode("Alerta no encontrada o ID inválido."));
    exit();
}

// Procesa la actualización si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : $alerta['descripcion'];
    $latitud = isset($_POST['latitud']) && $_POST['latitud'] !== '' ? floatval($_POST['latitud']) : $alerta['latitud'];
    $longitud = isset($_POST['longitud']) && $_POST['longitud'] !== '' ? floatval($_POST['longitud']) : $alerta['longitud'];
    $estado = isset($_POST['estado']) ? htmlspecialchars(trim($_POST['estado'])) : $alerta['estado'];
    $nivel_riesgo = isset($_POST['nivel_riesgo']) ? htmlspecialchars(trim($_POST['nivel_riesgo'])) : $alerta['nivel_riesgo'];
    $zona_geografica = isset($_POST['zona_geografica']) ? htmlspecialchars(trim($_POST['zona_geografica'])) : $alerta['zona_geografica'];

    // Actualizar la zona geográfica si la latitud o longitud han cambiado
    if ($latitud !== null && $longitud !== null) {
        $zona_geografica = "Formosa (Coord: " . $latitud . ", " . $longitud . ")";
    }

    $update_stmt = $conn->prepare("UPDATE alertas SET descripcion = ?, latitud = ?, longitud = ?, estado = ?, nivel_riesgo = ?, zona_geografica = ? WHERE id = ?");
    $update_stmt->bind_param("sddsssi", $descripcion, $latitud, $longitud, $estado, $nivel_riesgo, $zona_geografica, $alert_id);

    if ($update_stmt->execute()) {
        header("Location: admin.php?status=success&action=editada");
        exit();
    } else {
        header("Location: admin.php?status=error&message=" . urlencode("Error al actualizar la alerta: " . $update_stmt->error));
        exit();
    }
    $update_stmt->close();
}

$conn->close(); // Cerrar la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alerta - AquaLerta</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Editar Alerta #<?php echo $alerta['id']; ?></h1>
        <p>Actualiza la información de la alerta.</p>

        <form action="editar_alerta.php?id_alerta=<?php echo $alerta['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción de la situación:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($alerta['descripcion']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="latitud" class="form-label">Latitud:</label>
                <input type="text" class="form-control" id="latitud" name="latitud" value="<?php echo htmlspecialchars($alerta['latitud']); ?>">
            </div>
            <div class="mb-3">
                <label for="longitud" class="form-label">Longitud:</label>
                <input type="text" class="form-control" id="longitud" name="longitud" value="<?php echo htmlspecialchars($alerta['longitud']); ?>">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="pendiente" <?php if($alerta['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="activa" <?php if($alerta['estado'] == 'activa') echo 'selected'; ?>>Activa</option>
                    <option value="rechazada" <?php if($alerta['estado'] == 'rechazada') echo 'selected'; ?>>Rechazada</option>
                    <option value="resuelta" <?php if($alerta['estado'] == 'resuelta') echo 'selected'; ?>>Resuelta</option>
                    </select>
            </div>
            <div class="mb-3">
                <label for="nivel_riesgo" class="form-label">Nivel de Riesgo:</label>
                <select class="form-select" id="nivel_riesgo" name="nivel_riesgo">
                    <option value="bajo" <?php if($alerta['nivel_riesgo'] == 'bajo') echo 'selected'; ?>>Bajo</option>
                    <option value="medio" <?php if($alerta['nivel_riesgo'] == 'medio') echo 'selected'; ?>>Medio</option>
                    <option value="alto" <?php if($alerta['nivel_riesgo'] == 'alto') echo 'selected'; ?>>Alto</option>
                    <option value="critico" <?php if($alerta['nivel_riesgo'] == 'critico') echo 'selected'; ?>>Crítico</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="zona_geografica" class="form-label">Zona Geográfica (Auto-generado):</label>
                <input type="text" class="form-control" id="zona_geografica" name="zona_geografica" value="<?php echo htmlspecialchars($alerta['zona_geografica']); ?>" readonly>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="admin.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>