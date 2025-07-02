<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

$usuario_id = $_SESSION['user_id'];

// 1. Consulta SQL modificada para obtener las alertas que el usuario RECIBIÓ
// Unimos alertas_recibidas (para saber qué recibió el usuario y cuándo) con alertas (para los detalles de la alerta)
$stmt = $pdo->prepare("
    SELECT
        a.id_alerta,
        a.titulo,
        a.descripcion,
        a.fecha_hora,      -- Fecha y hora original de la alerta
        ar.fecha_envio,    -- Fecha en que el usuario recibió la alerta
        ar.leida           -- Estado de lectura
    FROM
        alertas_recibidas ar
    JOIN
        alertas a ON ar.id_alerta = a.id_alerta
    WHERE
        ar.id_usuario = ?
    ORDER BY
        ar.fecha_envio DESC
");
$stmt->execute([$usuario_id]);
$alertas_recibidas = $stmt->fetchAll(); // Renombramos la variable para mayor claridad
?>
<!DOCTYPE html>
<html>
<head>
    <title>Historial de Alertas Recibidas</title> <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container">
    <h2>Historial de Alertas Recibidas</h2> <?php if (empty($alertas_recibidas)): ?>
        <p>No tienes alertas recibidas en tu historial.</p>
    <?php else: ?>
        <ul class="list-group">
        <?php foreach($alertas_recibidas as $ar): // 2. Campos del bucle adaptados ?>
            <li class="list-group-item d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h5 class="mb-1"><?= htmlspecialchars($ar['titulo']) ?></h5>
                    <p class="mb-1"><?= htmlspecialchars($ar['descripcion']) ?></p>
                    <small class="text-muted">
                        Recibida: <?= $ar['fecha_envio'] ?> | Emitida: <?= $ar['fecha_hora'] ?>
                        <?php if ($ar['leida']): ?>
                            <span class="badge bg-success ms-2">Leída</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark ms-2">No Leída</span>
                        <?php endif; ?>
                    </small>
                </div>
                <a href="alerta.php?id=<?= $ar['id_alerta'] ?>" class="btn btn-info btn-sm">Ver Detalle</a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</body>
</html>