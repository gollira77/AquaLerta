<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

// Asegúrate de que las sesiones estén iniciadas si es necesario
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Obtener zonas de la base de datos para el dropdown
$stmt_zonas = $pdo->query("SELECT id_zona, descripcion FROM zonas ORDER BY descripcion ASC");
$zonas_db = $stmt_zonas->fetchAll(PDO::FETCH_ASSOC);

// Definir niveles de riesgo
$niveles_riesgo = ['Bajo', 'Medio', 'Alto', 'Crítico']; // Puedes ajustar estos según sea necesario

// Inicializar variables para mantener los valores en el formulario si hay un error
$titulo_prev = $_POST['titulo'] ?? '';
$descripcion_prev = $_POST['descripcion'] ?? '';
$nivel_riesgo_prev = $_POST['nivel_riesgo'] ?? '';
$id_zona_prev = $_POST['id_zona'] ?? '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $nivel_riesgo = $_POST['nivel_riesgo']; // Obtenido del formulario
    $id_zona = intval($_POST['id_zona']);   // Obtenido del formulario
    $usuario_id = $_SESSION['user_id'];
    $activa = 1; // Una alerta nueva siempre empieza como activa

    // Validaciones básicas
    if (empty($titulo) || empty($descripcion) || empty($nivel_riesgo) || empty($id_zona)) {
        $error = "Por favor, completa todos los campos requeridos.";
    } elseif (!in_array($nivel_riesgo, $niveles_riesgo)) {
        $error = "Nivel de riesgo seleccionado no es válido.";
    } else {
        // Validar que la zona seleccionada sea válida
        $zona_valida = false;
        foreach ($zonas_db as $z) {
            if ($z['id_zona'] == $id_zona) {
                $zona_valida = true;
                break;
            }
        }
        if (!$zona_valida && !empty($zonas_db)) {
            $error = "Por favor, selecciona una zona válida.";
        } else if (empty($zonas_db)) {
            $error = "No hay zonas disponibles para reportar alertas. Contacta al administrador.";
        } else {
            // 2. Insertar en la tabla 'alertas' con los campos correctos
            $stmt = $pdo->prepare("
                INSERT INTO alertas (titulo, descripcion, nivel_riesgo, fecha_hora, id_zona, emitida_por, activa)
                VALUES (?, ?, ?, NOW(), ?, ?, ?)
            ");
            try {
                $stmt->execute([$titulo, $descripcion, $nivel_riesgo, $id_zona, $usuario_id, $activa]);
                // Redirigir después de un reporte exitoso
                header('Location: historial.php?success=alerta_reportada');
                exit;
            } catch (PDOException $e) {
                $error = "Error al reportar la alerta: " . htmlspecialchars($e->getMessage());
                // Loggear el error real para depuración
                // error_log("Error al reportar alerta: " . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reportar Alerta</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container mt-4">
    <h2>Reportar Nueva Alerta</h2>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título de la Alerta</label>
            <input class="form-control" type="text" id="titulo" name="titulo" placeholder="Ej: Inundación en Barrio Centro" required value="<?= htmlspecialchars($titulo_prev) ?>">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción Detallada</label>
            <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Detalles de la situación, calles afectadas, etc." rows="4" required><?= htmlspecialchars($descripcion_prev) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="nivel_riesgo" class="form-label">Nivel de Riesgo</label>
            <select class="form-select" id="nivel_riesgo" name="nivel_riesgo" required>
                <option value="">Selecciona el nivel de riesgo</option>
                <?php foreach($niveles_riesgo as $nivel): ?>
                    <option value="<?= htmlspecialchars($nivel) ?>" <?= ($nivel_riesgo_prev == $nivel) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($nivel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_zona" class="form-label">Zona Afectada</label>
            <select class="form-select" id="id_zona" name="id_zona" required>
                <option value="">Selecciona la zona</option>
                <?php if (empty($zonas_db)): ?>
                    <option value="" disabled>No hay zonas disponibles</option>
                <?php else: ?>
                    <?php foreach($zonas_db as $zona): ?>
                        <option value="<?= $zona['id_zona'] ?>" <?= ($id_zona_prev == $zona['id_zona']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($zona['descripcion']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Reportar Alerta</button>
    </form>
</div>
</body>
</html>