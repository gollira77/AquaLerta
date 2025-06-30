<?php
// Incluir el archivo de configuración de la base de datos
include 'config.php';

// Consulta para obtener todos los reportes de ciudadanos
$sql = "SELECT id, descripcion, latitud, longitud, zona_geografica, foto_ruta, fecha_reporte, estado, comentarios_internos FROM reportes_ciudadanos ORDER BY fecha_reporte DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Reportes Ciudadanos - AquaLerta</title>
    
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
        /* Ajuste para imágenes en la tabla */
        .report-photo {
            max-width: 100px;
            height: auto;
            display: block; /* Para centrar la imagen si es necesario */
            margin: 0 auto;
        }
        .action-buttons {
            display: flex;
            flex-direction: column; /* Botones uno debajo del otro */
            gap: 5px;
            align-items: flex-start; /* Alinear a la izquierda */
        }
        .action-buttons form {
            margin: 0;
            width: 100%; /* Para que los select y botones ocupen el ancho completo */
        }
        .action-buttons select,
        .action-buttons button {
            width: 100%;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            cursor: pointer;
        }
        .action-buttons button {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .action-buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Reportes de Ciudadanos</h1>
        <p>Aquí puedes ver y gestionar los reportes enviados por la comunidad.</p>

        <?php
        // Mostrar mensajes de estado 
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<div class="alert alert-success" role="alert">¡Operación exitosa! Reporte ' . htmlspecialchars($_GET['action']) . '.</div>';
            } elseif ($_GET['status'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($_GET['message']) . '</div>';
            }
        }

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Fecha Reporte</th><th>Descripción</th><th>Barrio</th><th>Coordenadas</th><th>Foto</th><th>Estado</th><th>Comentarios Internos</th><th>Acciones</th></tr></thead>";
            echo "<tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . $row["fecha_reporte"]. "</td>";
                echo "<td>" . htmlspecialchars($row["descripcion"]). "</td>";
                echo "<td>" . htmlspecialchars($row["zona_geografica"]). "</td>";
                echo "<td>" . $row["latitud"] . ", " . $row["longitud"] . "</td>";
                echo "<td>";
                if (!empty($row["foto_ruta"]) && file_exists($row["foto_ruta"])) {
                    echo "<img src='" . htmlspecialchars($row["foto_ruta"]) . "' alt='Foto Reporte' class='report-photo'>";
                } else {
                    echo "N/A";
                }
                echo "</td>";
                echo "<td>" . $row["estado"]. "</td>";
                echo "<td>" . htmlspecialchars($row["comentarios_internos"] ?? 'Sin comentarios'). "</td>"; // Usar operador null coalescing para comentarios_internos
                echo "<td>"; 

                echo "<div class='action-buttons'>";
                
                // Formulario para cambiar el estado
                echo "<form action='actualizar_estado_reporte.php' method='POST'>";
                echo "<input type='hidden' name='id_reporte' value='" . $row["id"] . "'>";
                echo "<select name='nuevo_estado' onchange='this.form.submit()'>";
                echo "<option value='pendiente'" . ($row["estado"] == 'pendiente' ? ' selected' : '') . ">Pendiente</option>";
                echo "<option value='en_revision'" . ($row["estado"] == 'en_revision' ? ' selected' : '') . ">En Revisión</option>";
                echo "<option value='atendido'" . ($row["estado"] == 'atendido' ? ' selected' : '') . ">Atendido</option>";
                echo "</select>";
                echo "</form>";

                // Botón para editar comentarios 
                echo "<form action='editar_comentarios_reporte.php' method='GET'>";
                echo "<input type='hidden' name='id_reporte' value='" . $row["id"] . "'>";
                echo "<button type='submit'>Editar Comentarios</button>";
                echo "</form>";
                
                echo "</div>"; // Cierra el contenedor de botones
                echo "</td>"; // Cierra la celda de acciones
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay reportes de ciudadanos en el sistema.</p>";
        }
        $conn->close(); // Cerrar la conexión a la base de datos
        ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>