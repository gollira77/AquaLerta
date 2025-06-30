<?php
include 'config.php'; // Incluir el archivo de configuración de la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID de la alerta a eliminar
    $id_alerta = isset($_POST['id_alerta']) ? intval($_POST['id_alerta']) : 0;

    if ($id_alerta > 0) {
        // Preparar la consulta SQL para eliminar la alerta
        $stmt = $conn->prepare("DELETE FROM alertas WHERE id = ?");

        if ($stmt === false) {
            header("Location: admin.php?status=error&message=" . urlencode("Error al preparar la consulta de eliminación."));
            exit();
        }

        $stmt->bind_param("i", $id_alerta);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir de vuelta al panel de administración con un mensaje de éxito
            header("Location: admin.php?status=success&action=eliminada");
            exit();
        } else {
            // Redirigir con un mensaje de error
            header("Location: admin.php?status=error&message=" . urlencode("Error al eliminar la alerta: " . $stmt->error));
            exit();
        }

        $stmt->close();
    } else {
        // ID de alerta no válido
        header("Location: admin.php?status=error&message=" . urlencode("ID de alerta no válido para eliminar."));
        exit();
    }
} else {
    // Si no es una solicitud POST, redirigir al panel
    header("Location: admin.php");
    exit();
}

$conn->close(); // Cerrar la conexión a la base de datos
?>