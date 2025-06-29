<?php
// Incluir el archivo de configuración para la conexión a la BD
include 'config.php'; 

// Verificar que la solicitud sea POST 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $descripcion = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : '';
    $latitud = isset($_POST['latitud']) && $_POST['latitud'] !== '' ? floatval($_POST['latitud']) : null;
    $longitud = isset($_POST['longitud']) && $_POST['longitud'] !== '' ? floatval($_POST['longitud']) : null;

    // Validaciones básicas: Asegurarse de que la descripción no esté vacía
    if (empty($descripcion)) {
        echo "Error: La descripción no puede estar vacía.";
        exit; // Detener la ejecución si hay un error
    }

    // Determinar la zona geográfica 
    $zona_geografica = "Zona Desconocida";
    if ($latitud !== null && $longitud !== null) {
        $zona_geografica = "Formosa (Coord: " . $latitud . ", " . $longitud . ")";
    }

    $stmt = $conn->prepare("INSERT INTO alertas (descripcion, latitud, longitud, estado, nivel_riesgo, zona_geografica) VALUES (?, ?, ?, 'pendiente', 'bajo', ?)");

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $conn->error;
        exit;
    }

    // Vincular los parámetros a la consulta preparada
    $stmt->bind_param("sdds", $descripcion, $latitud, $longitud, $zona_geografica);

    // Ejecutar la consulta preparada
    if ($stmt->execute()) {
        // La alerta se guardó en la base de datos
        echo "success"; 
    } else {
        // Error al ejecutar la consulta
        echo "Error al guardar la alerta: " . $stmt->error;
    }

    // Cerrar la sentencia preparada
    $stmt->close();

    // Cerrar la conexión a la base de datos
    $conn->close();

} else {
    echo "Acceso denegado: Este script solo acepta solicitudes POST.";
}
?>