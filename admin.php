<?php
include 'config.php'; 

$sql = "SELECT id, descripcion, latitud, longitud, fecha_hora, estado FROM alertas WHERE estado = 'pendiente' ORDER BY fecha_hora DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Validación de Alertas - AquaLerta</title>
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
        }
        .btn-descartar {
            background-color: #dc3545; 
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Panel de Validación de Alertas</h1>
        <p>Aquí puedes ver y gestionar las alertas pendientes.</p>

        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Descripción</th><th>Latitud</th><th>Longitud</th><th>Fecha/Hora</th><th>Estado</th><th>Acciones</th></tr></thead>";
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
                echo "<td>";
                // Botones para confirmar o descartar la alerta
                echo "<form action='actualizar_estado_alerta.php' method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='id_alerta' value='" . $row["id"] . "'>";
                echo "<input type='hidden' name='nuevo_estado' value='activa'>"; // Estado para confirmar
                echo "<button type='submit' class='btn-confirmar'>Confirmar</button>";
                echo "</form> ";
                echo "<form action='actualizar_estado_alerta.php' method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='id_alerta' value='" . $row["id"] . "'>";
                echo "<input type='hidden' name='nuevo_estado' value='rechazada'>"; // Estado para descartar
                echo "<button type='submit' class='btn-descartar'>Descartar</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay alertas pendientes para validar.</p>";
        }
        $conn->close(); // Cerrar la conexión a la base de datos
        ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>