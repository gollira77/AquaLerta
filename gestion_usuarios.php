<?php
// Incluir el archivo de configuración de la base de datos
include 'config.php';

// Consulta para obtener todos los usuarios
$sql = "SELECT id, nombre_usuario, email, rol, estado, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - AquaLerta Admin</title>
    
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
        .action-buttons {
            display: flex;
            flex-wrap: wrap; 
            gap: 5px;
            justify-content: flex-start;
            align-items: center;
        }
        .action-buttons form {
            margin: 0;
        }
        .btn-edit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.85em;
            white-space: nowrap;
        }
        .btn-toggle-status {
            background-color: #ffc107; 
            color: black;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.85em;
            white-space: nowrap;
        }
        .btn-toggle-status.active-user {
            background-color: #28a745; 
            color: white;
        }
        .btn-toggle-status.inactive-user {
            background-color: #dc3545; 
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Gestión de Usuarios</h1>
        <p>Aquí puedes ver y administrar las cuentas de usuario registradas en el sistema.</p>

        <?php
        // Mostrar mensajes de estado 
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<div class="alert alert-success" role="alert">¡Operación exitosa! Usuario ' . htmlspecialchars($_GET['action']) . '.</div>';
            } elseif ($_GET['status'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($_GET['message']) . '</div>';
            }
        }

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Nombre de Usuario</th><th>Email</th><th>Rol</th><th>Estado</th><th>Fecha Registro</th><th>Acciones</th></tr></thead>";
            echo "<tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . htmlspecialchars($row["nombre_usuario"]). "</td>";
                echo "<td>" . htmlspecialchars($row["email"]). "</td>";
                echo "<td>" . htmlspecialchars($row["rol"]). "</td>";
                echo "<td>" . htmlspecialchars($row["estado"]). "</td>";
                echo "<td>" . $row["fecha_registro"]. "</td>";
                echo "<td>"; // Celda de acciones

                echo "<div class='action-buttons'>";
                
                // Botón para editar usuario
                echo "<form action='editar_usuario.php' method='GET'>";
                echo "<input type='hidden' name='id_usuario' value='" . $row["id"] . "'>";
                echo "<button type='submit' class='btn-edit'>Editar</button>";
                echo "</form>";

                // Botón para cambiar estado (Activar/Desactivar)
                $new_status = ($row["estado"] == 'activo') ? 'inactivo' : 'activo';
                $button_text = ($row["estado"] == 'activo') ? 'Desactivar' : 'Activar';
                $button_class = ($row["estado"] == 'activo') ? 'inactive-user' : 'active-user';

                echo "<form action='cambiar_estado_usuario.php' method='POST' onsubmit='return confirm(\"¿Estás seguro de que quieres " . strtolower($button_text) . " esta cuenta?\");'>";
                echo "<input type='hidden' name='id_usuario' value='" . $row["id"] . "'>";
                echo "<input type='hidden' name='nuevo_estado' value='" . $new_status . "'>";
                echo "<button type='submit' class='btn-toggle-status " . $button_class . "'>" . $button_text . "</button>";
                echo "</form>";
                
                echo "</div>"; 
                echo "</td>"; 
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay usuarios registrados en el sistema.</p>";
        }
        $conn->close(); // Cerrar la conexión a la base de datos
        ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>