<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT a.*, GROUP_CONCAT(u.usuario) as rescatistas
    FROM alertas a
    LEFT JOIN rescatistas_alerta ra ON a.id=ra.alerta_id
    LEFT JOIN usuarios u ON ra.rescatista_id=u.id
    WHERE a.id=?
    GROUP BY a.id");
$stmt->execute([$id]);
$alerta = $stmt->fetch();

if (!$alerta) {
    die("Alerta no encontrada.");
}

// --- API de clima con WeatherAPI.com ---
$apiKey = "1bbd2b3d2aa240668e872447250207"; // Cambia esto por tu API Key de WeatherAPI.com
$lat = "-26.1809"; // Puedes cambiar esto por la latitud de la alerta
$lon = "-58.2351"; // Puedes cambiar esto por la longitud de la alerta
$clima = null;
$errorApi = false;
$weather_url = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$lat,$lon&lang=es";
$weather_response = @file_get_contents($weather_url);
if ($weather_response) {
    $clima = json_decode($weather_response, true);
    if (!isset($clima['current'])) $errorApi = true;
} else {
    $errorApi = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalle Alerta - AquaLerta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/estilo.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate">
<?php include 'includes/navbar.php'; ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card p-4 rounded-4 shadow mb-4">
        <h2 class="text-primary mb-3"><?= htmlspecialchars($alerta['titulo']) ?></h2>
        <div class="mb-2"><?= htmlspecialchars($alerta['descripcion']) ?></div>
        <div class="text-muted small mb-3">Fecha: <?= $alerta['fecha'] ?></div>
        <div class="mb-3">
          <strong>Rescatistas participando:</strong>
          <?= $alerta['rescatistas'] ? htmlspecialchars($alerta['rescatistas']) : "Ninguno aún" ?>
        </div>
        <hr>
        <h4 class="text-primary mb-2"><i class="bi bi-cloud-sun"></i> Estado del clima actual</h4>
        <?php if(!$errorApi && $clima): ?>
          <div class="d-flex gap-3 align-items-center mb-2">
            <div>
              <span class="fs-2"><?= intval($clima['current']['temp_c']) ?>&deg;C</span>
              <span class="text-secondary"><?= ucfirst($clima['current']['condition']['text']) ?></span>
            </div>
            <img src="https:<?= $clima['current']['condition']['icon'] ?>" alt="icono clima">
          </div>
          <div class="small text-secondary">
            Humedad: <?= $clima['current']['humidity'] ?>% |
            Viento: <?= $clima['current']['wind_kph'] ?> km/h
          </div>
        <?php else: ?>
          <div class="alert alert-warning small">No se pudo obtener el clima actual. Verifica tu conexión o tu API KEY.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>