<?php
require_once 'funciones.php';
redirect_if_not_logged_in();
$role = get_user_role();
if ($role == 'admin') header('Location: admin.php');
if ($role == 'rescatista') header('Location: rescatista.php');
if ($role == 'medio') header('Location: medio.php');

// Obtener alertas y cantidad de pendientes
$stmt = $pdo->query("SELECT a.*, GROUP_CONCAT(u.usuario) as rescatistas
    FROM alertas a
    LEFT JOIN rescatistas_alerta ra ON a.id=ra.alerta_id
    LEFT JOIN usuarios u ON ra.rescatista_id=u.id
    GROUP BY a.id
    ORDER BY a.fecha DESC");
$alertas = $stmt->fetchAll();
$alertas_pendientes = count($alertas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AquaLerta - Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/estilo.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate">
  <?php include 'includes/navbar.php'; ?>
  <main class="container pt-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card p-4 rounded-4 shadow mb-4">
          <h3 class="mb-3 text-primary">Alertas Activas</h3>
          <?php
          if (empty($alertas)) {
            echo "<p class='text-secondary'>No hay alertas activas.</p>";
          } else {
            foreach ($alertas as $alerta) {
              echo "<div class='alerta-card mb-3 p-3 rounded-3 bg-light position-relative'>";
              echo "<h5 class='text-primary-emphasis mb-1'>" . htmlspecialchars($alerta['titulo']) . "</h5>";
              echo "<div class='small text-muted mb-1'>Fecha: " . $alerta['fecha'] . "</div>";
              echo "<div class='mb-2'>" . htmlspecialchars($alerta['descripcion']) . "</div>";
              echo "<div class='small'><span class='fw-semibold'>Rescatistas participando:</span> ";
              echo ($alerta['rescatistas']) ? htmlspecialchars($alerta['rescatistas']) : "Ninguno aún";
              echo "</div>";
              // Enlace oculto para hacer toda la tarjeta clickeable
              echo "<a href='alerta.php?id=" . $alerta['id'] . "' class='stretched-link'></a>";
              echo "</div>";
            }
          }
          ?>
        </div>
        <div class="card p-4 rounded-4 shadow mb-4">
          <h3 class="mb-3 text-primary">Módulo Educativo</h3>
          <a href="educacion.php" class="btn btn-primary btn-lg px-4 py-2 rounded-3">Ir al módulo educativo</a>
        </div>
      </div>
    </div>
  </main>
</body>
</html>