<?php
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados por el formulario
    $id_alerta = $_POST['id_alerta'];
    $nuevo_estado = $_POST['nuevo_estado'];

    // Validar que el nuevo estado sea 'activa' o 'rechazada'
    if ($nuevo_estado == 'activa' || $nuevo_estado == 'rechazada') {
        // Preparar la consulta SQL para actualizar el estado
        $stmt = $conn->prepare("UPDATE alertas SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevo_estado, $id_alerta); // "s" para string, "i" para integer

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir de vuelta al panel de administración con un mensaje de éxito
            header("Location: admin.php?status=success&action=" . $nuevo_estado);
            exit();
        } else {
            // Redirigir con un mensaje de error
            header("Location: admin.php?status=error&message=" . urlencode($stmt->error));
            exit();
        }

        $stmt->close();
    } else {
        // Estado no válido
        header("Location: admin.php?status=error&message=" . urlencode("Estado no válido."));
        exit();
    }
} else {
    // Si no es una solicitud POST, redirigir al panel
    header("Location: admin.php");
    exit();
}

$conn->close(); // Cerrar la conexión a la base de datos
?>