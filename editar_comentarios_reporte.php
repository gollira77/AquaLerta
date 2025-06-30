<?php
error_reporting(E_ALL); // Muestra todos los errores
ini_set('display_errors', 1); // Asegura que los errores se muestren en el navegador

include 'config.php';

// Añade estas líneas para depurar
var_dump($_GET);
if (!isset($_GET['id_reporte'])) {
    die("DEBUG: id_reporte NO está en GET. Esto es lo que se recibe: " . json_encode($_GET));
}
// Fin de depuración

$reporte_id = $_GET['id_reporte'] ?? null;
$reporte = null;

// Resto de tu código
if ($reporte_id) {
} else {
    header("Location: reportes.php?status=error&message=ID de reporte no especificado.");
    exit();
}
?>