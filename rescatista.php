<?php
require_once 'funciones.php';
redirect_if_not_logged_in();
if (get_user_role() != 'rescatista') die('Acceso denegado');

$rescatista_id = $_SESSION['user_id'];

// Unirse a alerta
if (isset($_GET['unir']) && is_numeric($_GET['unir'])) {
    $alerta_id = intval($_GET['unir']);
    $stmt = $pdo->prepare("SELECT * FROM rescatistas_alerta WHERE alerta_id=? AND rescatista_id=?");
    $stmt->execute([$alerta_id, $rescatista_id]);
    if (!$stmt->fetch()) {
        $pdo->prepare("INSERT INTO rescatistas_alerta (alerta_id, rescatista_id) VALUES (?,?)")
            ->execute([$alerta_id, $rescatista_id]);
    }
    header('Location: rescatista.php');
    exit;
}

// Dejar de participar
if (isset($_GET['salir']) && is_numeric($_GET['salir'])) {
    $alerta_id = intval($_GET['salir']);
    $pdo->prepare("DELETE FROM rescatistas_alerta WHERE alerta_id=? AND rescatista_id=?")
        ->execute([$alerta_id, $rescatista_id]);
    header('Location: rescatista.php');
    exit;
}

$stmt = $pdo->query("SELECT a.*, 
    EXISTS(SELECT 1 FROM rescatistas_alerta ra WHERE ra.alerta_id=a.id AND ra.rescatista_id=$rescatista_id) as unido
    FROM alertas a ORDER BY a.fecha DESC");
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
          <h3>Alertas Disponibles</h3>
        </div>
        <div class="card-body">
          <?php
          foreach ($alertas as $a) {
            echo "<div class='alerta-card mb-3 p-3 rounded-3 bg-light'>";
            echo "<h5 class='text-primary-emphasis mb-1'>" . htmlspecialchars($a['titulo']) . "</h5>";
            echo "<div class='small text-muted mb-1'>Fecha: " . $a['fecha'] . "</div>";
            echo "<div class='mb-2'>" . htmlspecialchars($a['descripcion']) . "</div>";
            if ($a['unido']) {
              echo "<span class='badge bg-success mb-2'><i class='bi bi-check-circle me-1'></i>Te uniste a esta alerta</span><br>";
              echo "<a href='rescatista.php?salir=" . $a['id'] . "' class='btn btn-outline-danger btn-sm mt-2'><i class='bi bi-x-circle'></i> Dejar de participar</a>";
            } else {
              echo "<a href='rescatista.php?unir=" . $a['id'] . "' class='btn btn-primary btn-sm mt-2'><i class='bi bi-plus-circle'></i> Unirme a ayudar</a>";
            }
            echo "</div>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>