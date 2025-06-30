<?php
include 'config.php'; 

// Consulta para obtener todas las alertas 
// Ordenadas por fecha/hora para ver las más recientes primero
$sql = "SELECT id, descripcion, latitud, longitud, fecha_hora, estado, nivel_riesgo, zona_geografica FROM alertas ORDER BY fecha_hora DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Gestión de Alertas - AquaLerta</title>
    
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
        .btn-confirmar {
            background-color: #28a745; 
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        .btn-descartar {
            background-color: #dc3545; 
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        .btn-editar {
            background-color: #007bff; 
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        .btn-resolver {
            background-color: #6c757d; 
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Panel de Gestión de Alertas</h1>
        <p>Aquí puedes ver, validar, editar y cerrar todas las alertas.</p>

        <?php
        // Mostrar mensajes de estado 
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<div class="alert alert-success" role="alert">¡Operación exitosa! Alerta ' . htmlspecialchars($_GET['action']) . '.</div>';
            } elseif ($_GET['status'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($_GET['message']) . '</div>';
            }
        }

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Descripción</th><th>Latitud</th><th>Longitud</th><th>Fecha/Hora</th><th>Estado</th><th>Nivel Riesgo</th><th>Zona Geográfica</th><th>Acciones</th></tr></thead>";
            echo "<tbody>";
            // Mostrar cada fila de resultados
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . htmlspecialchars($row["descripcion"]). "</td>";
                echo "<td>" . $row["latitud"]. "</td>";
                echo "<td>" . $row["longitud"]. "</td>";
                echo "<td>" . $row["fecha_hora"]. "</td>";
                echo "<td>" . $row["estado"]. "</td>";
                echo "<td>" . $row["nivel_riesgo"]. "</td>"; // Mostrar nivel de riesgo
                echo "<td>" . htmlspecialchars($row["zona_geografica"]). "</td>"; // Mostrar zona geográfica
                echo "<td>";

                // Botones de Confirmar/Descartar solo si el estado es 'pendiente'
                if ($row["estado"] == 'pendiente') {
                    echo "<form action='actualizar_estado_alerta.php' method='POST' style='display:inline-block; margin-right: 5px;'>";
                    echo "<input type='hidden' name='id_alerta' value='" . $row["id"] . "'>";
                    echo "<input type='hidden' name='nuevo_estado' value='activa'>";
                    echo "<button type='submit' class='btn-confirmar'>Confirmar</button>";
                    echo "</form>";
                    echo "<form action='actualizar_estado_alerta.php' method='POST' style='display:inline-block;'>";
                    echo "<input type='hidden' name='id_alerta' value='" . $row["id"] . "'>";
                    echo "<input type='hidden' name='nuevo_estado' value='rechazada'>";
                    echo "<button type='submit' class='btn-descartar'>Descartar</button>";
                    echo "</form>";
                } 
                
                // Botones de Editar y Resolver para alertas activas (o incluso pendientes/rechazadas si se quisiera reabrir)
                if ($row["estado"] == 'activa' || $row["estado"] == 'pendiente' || $row["estado"] == 'rechazada') {
                    echo "<form action='editar_alerta.php' method='GET' style='display:inline-block; margin-right: 5px;'>";
                    echo "<input type='hidden' name='id_alerta' value='" . $row["id"] . "'>";
                    echo "<button type='submit' class='btn-editar'>Editar</button>";
                    echo "</form>";
                }
                
                // Botón de Marcar como Resuelta solo si la alerta no está ya resuelta
                if ($row["estado"] != 'resuelta') {
                    echo "<form action='actualizar_estado_alerta.php' method='POST' style='display:inline-block;'>";
                    echo "<input type='hidden' name='id_alerta' value='" . $row["id"] . "'>";
                    echo "<input type='hidden' name='nuevo_estado' value='resuelta'>";
                    echo "<button type='submit' class='btn-resolver'>Marcar Resuelta</button>";
                    echo "</form>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay alertas en el sistema para gestionar.</p>";
        }
        $conn->close(); // Cerrar la conexión a la base de datos
        ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>