<?php
include 'config.php';

// Consulta para obtener solo las alertas activas
$sql = "SELECT id, descripcion, latitud, longitud, fecha_hora, estado, nivel_riesgo, zona_geografica FROM alertas WHERE estado = 'activa' ORDER BY fecha_hora DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Alertas Activas - AquaLerta</title>
    
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
        /* Estilos para colorear filas según nivel de riesgo */
        .riesgo-bajo {
            background-color: #d4edda; 
            color: #155724;
        }
        .riesgo-medio {
            background-color: #fff3cd; 
            color: #856404;
        }
        .riesgo-alto {
            background-color: #f8d7da; 
            color: #721c24;
        }
        .riesgo-critico {
            background-color: #f5c6cb; 
            color: #721c24;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Alertas Activas por Zona</h1>
        <p>Aquí puedes ver todas las alertas que se encuentran activas actualmente, clasificadas por su nivel de riesgo.</p>

        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Fecha/Hora</th><th>Nivel Riesgo</th><th>Descripción</th><th>Latitud</th><th>Longitud</th><th>Zona Geográfica</th></tr></thead>";
            echo "<tbody>";
            // Mostrar cada fila de resultados
            while($row = $result->fetch_assoc()) {
                // Determinar la clase CSS según el nivel de riesgo
                $clase_riesgo = '';
                switch ($row["nivel_riesgo"]) {
                    case 'bajo':
                        $clase_riesgo = 'riesgo-bajo';
                        break;
                    case 'medio':
                        $clase_riesgo = 'riesgo-medio';
                        break;
                    case 'alto':
                        $clase_riesgo = 'riesgo-alto';
                        break;
                    case 'critico':
                        $clase_riesgo = 'riesgo-critico';
                        break;
                    default:
                        $clase_riesgo = ''; // Sin clase si no coincide
                }

                echo "<tr class='" . $clase_riesgo . "'>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . $row["fecha_hora"]. "</td>";
                echo "<td>" . $row["nivel_riesgo"]. "</td>";
                echo "<td>" . htmlspecialchars($row["descripcion"]). "</td>";
                echo "<td>" . $row["latitud"]. "</td>";
                echo "<td>" . $row["longitud"]. "</td>";
                echo "<td>" . htmlspecialchars($row["zona_geografica"]). "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay alertas activas en este momento.</p>";
        }
        $conn->close(); // Cerrar la conexión a la base de datos
        ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>