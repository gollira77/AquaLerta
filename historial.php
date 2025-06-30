<?php
include 'config.php';

// Inicializar variables para los filtros y los resultados
$fecha_inicio = '';
$fecha_fin = '';
$barrio = '';
$result = null;
$sql = "SELECT id, descripcion, latitud, longitud, fecha_hora, estado, nivel_riesgo, zona_geografica FROM alertas WHERE 1=1"; 

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['buscar'])) {
    // Recoger los valores del formulario
    $fecha_inicio = $_GET['fecha_inicio'] ?? '';
    $fecha_fin = $_GET['fecha_fin'] ?? '';
    $barrio = $_GET['barrio'] ?? '';

    // Construir la consulta SQL dinámicamente
    if (!empty($fecha_inicio)) {
        $sql .= " AND fecha_hora >= '" . $conn->real_escape_string($fecha_inicio) . " 00:00:00'";
    }
    if (!empty($fecha_fin)) {
        $sql .= " AND fecha_hora <= '" . $conn->real_escape_string($fecha_fin) . " 23:59:59'";
    }
    if (!empty($barrio)) {
        // Usar LIKE para búsqueda parcial o exacta del barrio
        $sql .= " AND zona_geografica LIKE '%" . $conn->real_escape_string($barrio) . "%'";
    }

    $sql .= " ORDER BY fecha_hora DESC"; // Ordenar por fecha/hora las más recientes primero

    // Ejecutar la consulta
    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }
} else {
    // Si no se ha enviado el formulario de búsqueda, mostrar todas las alertas por defecto 
    $sql_all = "SELECT id, descripcion, latitud, longitud, fecha_hora, estado, nivel_riesgo, zona_geografica FROM alertas ORDER BY fecha_hora DESC";
    $result = $conn->query($sql_all);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Alertas - AquaLerta</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilo.css">
    <style>
        /* Estilos básicos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .filter-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .filter-form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .filter-form input[type="date"],
        .filter-form input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
        }
        .filter-form button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .filter-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Historial de Alertas</h1>
        <p>Busca alertas pasadas filtrando por rango de fechas y/o barrio.</p>

        <div class="filter-form">
            <form action="historial.php" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label for="fecha_inicio">Fecha de Inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="fecha_fin">Fecha de Fin:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="barrio">Barrio:</label>
                        <input type="text" id="barrio" name="barrio" value="<?php echo htmlspecialchars($barrio); ?>">
                    </div>
                </div>
                <button type="submit" name="buscar">Buscar Alertas</button>
            </form>
        </div>

        <?php
        if ($result && $result->num_rows > 0) {
            echo "<h2>Resultados de la búsqueda:</h2>";
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Fecha/Hora</th><th>Nivel Riesgo</th><th>Descripción</th><th>Latitud</th><th>Longitud</th><th>Estado</th><th>Zona Geográfica</th></tr></thead>";
            echo "<tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . $row["fecha_hora"]. "</td>";
                echo "<td>" . $row["nivel_riesgo"]. "</td>";
                echo "<td>" . htmlspecialchars($row["descripcion"]). "</td>";
                echo "<td>" . $row["latitud"]. "</td>";
                echo "<td>" . $row["longitud"]. "</td>";
                echo "<td>" . $row["estado"]. "</td>";
                echo "<td>" . htmlspecialchars($row["zona_geografica"]). "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['buscar'])) {
            echo "<p>No se encontraron alertas con los filtros especificados.</p>";
        } else 
            echo "<p>Utiliza los filtros de arriba para buscar alertas en el historial.</p>";
        ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>