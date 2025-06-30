<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_reporte']) && isset($_POST['nuevo_estado'])) {
    $id_reporte = $conn->real_escape_string($_POST['id_reporte']);
    $nuevo_estado = $conn->real_escape_string($_POST['nuevo_estado']);

    // Validar que el nuevo estado sea uno de los permitidos
    $estados_permitidos = ['pendiente', 'en_revision', 'atendido'];
    if (!in_array($nuevo_estado, $estados_permitidos)) {
        header("Location: reportes.php?status=error&message=Estado no válido.");
        exit();
    }

    $sql = "UPDATE reportes_ciudadanos SET estado = '$nuevo_estado' WHERE id = '$id_reporte'";

    if ($conn->query($sql) === TRUE) {
        header("Location: reportes.php?status=success&action=actualizado_estado");
    } else {
        header("Location: reportes.php?status=error&message=" . urlencode($conn->error));
    }
    $conn->close();
} else {
    header("Location: reportes.php?status=error&message=Acceso no válido.");
}
exit();
?>