<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

$id_alerta = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_barrio_seleccionado = isset($_GET['id_barrio_seleccionado']) ? intval($_GET['id_barrio_seleccionado']) : 0;

// 1. Consulta principal para obtener los detalles de la alerta y su id_zona
$stmt = $pdo->prepare("SELECT a.*, z.id_zona, z.zona, GROUP_CONCAT(u.nombre) as usuarios_recibieron
    FROM alertas a
    JOIN zonas z ON a.id_zona = z.id_zona
    LEFT JOIN alertas_recibidas ar ON a.id_alerta = ar.id_alerta
    LEFT JOIN usuarios u ON ar.id_usuario = u.id_usuario
    WHERE a.id_alerta = ?
    GROUP BY a.id_alerta, z.id_zona, z.zona"); // Agregamos z.id_zona y z.zona al GROUP BY
$stmt->execute([$id_alerta]);
$alerta = $stmt->fetch();

if (!$alerta) {
    die("Alerta no encontrada.");
}

$id_zona_alerta = $alerta['id_zona'];

// 2. Consulta para obtener todos los barrios de la zona de la alerta
$stmt_barrios = $pdo->prepare("SELECT id_barrio, barrio, latitud, longitud 
                            FROM barrios 
                            WHERE id_zona = ? 
                            ORDER BY barrio ASC");
$stmt_barrios->execute([$id_zona_alerta]);
$barrios_en_zona = $stmt_barrios->fetchAll(PDO::FETCH_ASSOC);

$lat_clima = null;
$lon_clima = null;
$nombre_barrio_clima = "No especificado";

// 3. Determinar la latitud y longitud a usar para el clima
if ($id_barrio_seleccionado > 0) {
    // Buscar el barrio seleccionado en la lista de barrios de la zona
    foreach ($barrios_en_zona as $b) {
        if ($b['id_barrio'] == $id_barrio_seleccionado) {
            $lat_clima = $b['latitud'];
            $lon_clima = $b['longitud'];
            $nombre_barrio_clima = htmlspecialchars($b['barrio']);
            break;
        }
    }
} 

// Si no se seleccionó un barrio válido o no se encontró, usar el primer barrio de la zona como predeterminado
if (empty($lat_clima) && !empty($barrios_en_zona)) {
    $lat_clima = $barrios_en_zona[0]['latitud'];
    $lon_clima = $barrios_en_zona[0]['longitud'];
    $id_barrio_seleccionado = $barrios_en_zona[0]['id_barrio']; // Establecer el ID del barrio por defecto
    $nombre_barrio_clima = htmlspecialchars($barrios_en_zona[0]['barrio']);
}

// --- API de clima con WeatherAPI.com ---
$apiKey = "1bbd2b3d2aa240668e872447250207"; // Cambia esto por tu API Key de WeatherAPI.com
$clima = null;
$errorApi = false;

if ($lat_clima && $lon_clima) {
    $weather_url = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$lat_clima,$lon_clima&lang=es";
    $weather_response = @file_get_contents($weather_url);
    if ($weather_response) {
        $clima = json_decode($weather_response, true);
        if (!isset($clima['current'])) $errorApi = true;
    } else {
        $errorApi = true;
    }
} else {
    $errorApi = true; // No hay coordenadas para consultar el clima
    $nombre_barrio_clima = "No disponible";
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
                <div class="text-muted small mb-3">Fecha: <?= $alerta['fecha_hora'] ?></div>
                <div class="mb-3">
                    <strong>Usuarios que recibieron esta alerta:</strong>
                    <?= $alerta['usuarios_recibieron'] ? htmlspecialchars($alerta['usuarios_recibieron']) : "Ninguno aún" ?>
                </div>
                <hr>
                <h4 class="text-primary mb-2"><i class="bi bi-cloud-sun"></i> Estado del clima actual</h4>
                
                <form action="alerta.php" method="GET" class="mb-3">
                    <input type="hidden" name="id" value="<?= $id_alerta ?>">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label for="barrio_selector" class="col-form-label">Clima para el barrio:</label>
                        </div>
                        <div class="col-md-6">
                            <select name="id_barrio_seleccionado" id="barrio_selector" class="form-select">
                                <?php if (empty($barrios_en_zona)): ?>
                                    <option value="">No hay barrios para esta zona</option>
                                <?php else: ?>
                                    <?php foreach ($barrios_en_zona as $b): ?>
                                        <option value="<?= $b['id_barrio'] ?>" 
                                            <?= ($b['id_barrio'] == $id_barrio_seleccionado) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($b['barrio']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Ver Clima</button>
                        </div>
                    </div>
                </form>

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
                        <br>
                        <small>Ubicación: <?= $nombre_barrio_clima ?> (Zona: <?= htmlspecialchars($alerta['zona']) ?>)</small>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning small">
                        No se pudo obtener el clima actual para el barrio seleccionado (<?= $nombre_barrio_clima ?>). Verifica tu conexión, tu API KEY o si el barrio tiene coordenadas válidas.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>