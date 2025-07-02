<?php
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $descripcion = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : '';
    $latitud = isset($_POST['latitud']) && $_POST['latitud'] !== '' ? floatval($_POST['latitud']) : null;
    $longitud = isset($_POST['longitud']) && $_POST['longitud'] !== '' ? floatval($_POST['longitud']) : null;

    if (empty($descripcion)) {
        echo "Error: La descripción de la alerta no puede estar vacía.";
        exit; 
    }

    $titulo = "Reporte de alerta - " . substr($descripcion, 0, 50) . "..."; 
    $id_zona = 1; 

    if (!isset($_SESSION['user_id'])) {
        $emitida_por = 0; 
        echo "Error: Se requiere un usuario logueado para reportar alertas.";
        exit;
    }
    $emitida_por = $_SESSION['user_id'];

    $nivel_riesgo = 'bajo'; 

    $activa = 1; 

    $stmt = $pdo->prepare("INSERT INTO alertas (titulo, descripcion, nivel_riesgo, fecha_hora, id_zona, emitida_por, activa) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
    
    try {
        $stmt->execute([$titulo, $descripcion, $nivel_riesgo, $id_zona, $emitida_por, $activa]);
        echo "success"; 
    } catch (\PDOException $e) {
        echo "Error al guardar la alerta: " . $e->getMessage();
    }

} else {
    echo "Acceso denegado: Este script solo acepta solicitudes POST.";
}
?>