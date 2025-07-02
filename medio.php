<?php
require_once 'funciones.php';
redirect_if_not_logged_in();
if (get_user_role() != 'medio') die('Acceso denegado');

// Mostrar mensajes de éxito o error
$mensaje = "";

// Eliminar alerta (cualquier medio puede)
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $alerta_id = intval($_GET['eliminar']);
    // Chequear si la alerta existe
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM alertas WHERE id = ?");
    $stmt_check->execute([$alerta_id]);
    if ($stmt_check->fetchColumn() > 0) {
        // Eliminar participaciones de rescatistas
        $stmt2 = $pdo->prepare("DELETE FROM rescatistas_alerta WHERE alerta_id = ?");
        $stmt2->execute([$alerta_id]);
        // Eliminar la alerta
        $stmt = $pdo->prepare("DELETE FROM alertas WHERE id = ?");
        $stmt->execute([$alerta_id]);
        $mensaje = "<div class='alert alert-success'>Alerta eliminada correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>La alerta no existe.</div>";
    }
}

// Crear alerta (solo añade alerta con tu usuario)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $usuario_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("INSERT INTO alertas (titulo, descripcion, usuario_id, fecha) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$titulo, $descripcion, $usuario_id]);
    header('Location: medio.php');
    exit;
}

// Listar todas las alertas (no solo las propias)
$stmt = $pdo->query("SELECT * FROM alertas ORDER BY fecha DESC");
$alertas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Panel Medio - AquaLerta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/estilo.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate">
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4>Crear nueva alerta</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input class="form-control mb-2" name="titulo" placeholder="Título" required>
                        <textarea class="form-control mb-2" name="descripcion" placeholder="Descripción" required></textarea>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-plus-circle"></i> Crear alerta
                        </button>
                    </form>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4>Todas las alertas</h4>
                </div>
                <div class="card-body">
                    <?= $mensaje ?>
                    <?php
                    if (empty($alertas)) {
                        echo "<p class='text-secondary'>No hay alertas todavía.</p>";
                    } else {
                        foreach($alertas as $a){
                            echo "<div class='alerta-card mb-3 p-3 rounded-3 bg-light position-relative'>";
                            echo "<h5 class='text-primary-emphasis mb-1'>" . htmlspecialchars($a['titulo']) . "</h5>";
                            echo "<div class='small text-muted mb-1'>Fecha: " . $a['fecha'] . "</div>";
                            echo "<div class='mb-2'>" . htmlspecialchars($a['descripcion']) . "</div>";
                            echo "<a href='alerta.php?id=" . $a['id'] . "' class='stretched-link'></a>";
                            echo "<a href='medio.php?eliminar=" . $a['id'] . "' class='btn btn-outline-danger btn-sm position-absolute top-0 end-0 m-2' onclick=\"return confirm('¿Seguro que quieres eliminar esta alerta?');\">";
                            echo "<i class='bi bi-trash'></i> Eliminar</a>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>