<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

// Asegúrate de que las sesiones estén iniciadas si no lo están ya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Rol de usuario verificado con el nombre de rol de la base de datos
// 'Rescatista' debe coincidir con el valor en la columna 'tipo' de la tabla 'tipos_usuarios'
if (get_user_role() != 'Rescatista') {
    die('Acceso denegado: Este panel es solo para Rescatistas.');
}

$rescatista_id = $_SESSION['user_id'];

// Lógica para 'Unirse a Alerta'
if (isset($_GET['unir']) && is_numeric($_GET['unir'])) {
    $alerta_id = intval($_GET['unir']);

    // 2. Eliminación de prefijo 'aqualerta_' en aqualerta_alertas_recibidas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM alertas_recibidas WHERE id_alerta = ? AND id_usuario = ?");
    $stmt->execute([$alerta_id, $rescatista_id]);
    
    if ($stmt->fetchColumn() == 0) {
        // 2. Eliminación de prefijo 'aqualerta_'
        // 3. Establecer 'leida' a 0 si la acción es "unirse" (el rescatista está tomando acción sobre ella, no solo leyéndola pasivamente)
        $pdo->prepare("INSERT INTO alertas_recibidas (id_alerta, id_usuario, fecha_envio, leida) VALUES (?, ?, NOW(), 0)")
            ->execute([$alerta_id, $rescatista_id]);
    }
    header('Location: rescatista.php');
    exit;
}

// Lógica para 'Salir de Alerta'
if (isset($_GET['salir']) && is_numeric($_GET['salir'])) {
    $alerta_id = intval($_GET['salir']);
    // 2. Eliminación de prefijo 'aqualerta_'
    $pdo->prepare("DELETE FROM alertas_recibidas WHERE id_alerta = ? AND id_usuario = ?")
        ->execute([$alerta_id, $rescatista_id]);
    header('Location: rescatista.php');
    exit;
}

// Consulta principal para mostrar alertas
// 2. Eliminación de prefijo 'aqualerta_' en la subconsulta EXISTS
// 4. Filtrar solo alertas activas para el panel del rescatista (a.activa = 1)
$stmt = $pdo->prepare("
    SELECT a.*, 
    EXISTS(SELECT 1 FROM alertas_recibidas aar WHERE aar.id_alerta = a.id_alerta AND aar.id_usuario = ?) as unido
    FROM alertas a 
    WHERE a.activa = 1 -- Solo alertas activas
    ORDER BY a.fecha_hora DESC
"); 
$stmt->execute([$rescatista_id]); 
$alertas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Panel Rescatista - AquaLerta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/estilo.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate">
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h3>Alertas Disponibles para Rescate</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($alertas)): ?>
                        <div class="alert alert-info" role="alert">
                            No hay alertas activas disponibles en este momento. ¡Gracias por tu disposición!
                        </div>
                    <?php else: ?>
                        <?php foreach ($alertas as $a): ?>
                            <div class='alerta-card mb-3 p-3 rounded-3 bg-light'>
                                <h5 class='text-primary-emphasis mb-1'>" . htmlspecialchars($a['titulo']) . "</h5>
                                <div class='small text-muted mb-1'>Fecha: " . htmlspecialchars($a['fecha_hora']) . "</div>
                                <div class='small text-muted mb-1'>Nivel de Riesgo: <span class='fw-semibold text-<?= strtolower($a['nivel_riesgo']) == 'alto' || strtolower($a['nivel_riesgo']) == 'crítico' ? 'danger' : (strtolower($a['nivel_riesgo']) == 'medio' ? 'warning' : 'info') ?>'><?= htmlspecialchars($a['nivel_riesgo']) ?></span></div>
                                <div class='mb-2'>" . htmlspecialchars($a['descripcion']) . "</div>
                                <div class='small text-muted mb-1'>Zona: 
                                    <?php
                                    // Buscar la descripción de la zona
                                    $zona_descripcion = 'Desconocida';
                                    foreach ($zonas_db as $zona_item) {
                                        if ($zona_item['id_zona'] == $a['id_zona']) {
                                            $zona_descripcion = $zona_item['descripcion'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($zona_descripcion);
                                    ?>
                                </div>

                                <?php if ($a['unido']): ?>
                                    <span class='badge bg-success mb-2'><i class='bi bi-check-circle me-1'></i>Te uniste a esta alerta</span><br>
                                    <a href='rescatista.php?salir=" . $a['id_alerta'] . "' class='btn btn-outline-danger btn-sm mt-2'><i class='bi bi-x-circle'></i> Dejar de participar</a>
                                <?php else: ?>
                                    <a href='rescatista.php?unir=" . $a['id_alerta'] . "' class='btn btn-primary btn-sm mt-2'><i class='bi bi-plus-circle'></i> Unirme a ayudar</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>