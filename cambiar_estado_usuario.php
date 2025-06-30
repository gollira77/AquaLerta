<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_usuario']) && isset($_POST['nuevo_estado'])) {
    $id_usuario = $conn->real_escape_string($_POST['id_usuario']);
    $nuevo_estado = $conn->real_escape_string($_POST['nuevo_estado']);

    // Validar que el nuevo estado sea uno de los permitidos
    $estados_permitidos = ['activo', 'inactivo'];
    if (!in_array($nuevo_estado, $estados_permitidos)) {
        header("Location: gestion_usuarios.php?status=error&message=Estado no válido.");
        exit();
    }

    // Obtener el estado actual para el log de auditoría
    $old_data_sql = "SELECT estado FROM usuarios WHERE id = '$id_usuario'";
    $old_data_result = $conn->query($old_data_sql);
    $old_data = $old_data_result->fetch_assoc();
    $old_estado = $old_data['estado'];

    $sql = "UPDATE usuarios SET estado = '$nuevo_estado' WHERE id = '$id_usuario'";

    if ($conn->query($sql) === TRUE) {
        // Registrar en el log de auditoría
        $descripcion_cambio = "Usuario ID: " . $id_usuario . " - Estado cambiado de '" . $old_estado . "' a '" . $nuevo_estado . "'.";
        
        $admin_id_auditoria = 1; // ID de un admin de prueba, o NULL/manejar con la sesión

        $log_sql = "INSERT INTO log_auditoria (usuario_id, accion, tabla_afectada, registro_id_afectado, descripcion_cambio) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($log_sql);
        $accion = 'cambiar_estado_usuario';
        $tabla_afectada = 'usuarios';
        $stmt->bind_param("isiss", $admin_id_auditoria, $accion, $tabla_afectada, $id_usuario, $descripcion_cambio);
        $stmt->execute();
        $stmt->close();

        header("Location: gestion_usuarios.php?status=success&action=estado_actualizado");
    } else {
        header("Location: gestion_usuarios.php?status=error&message=" . urlencode($conn->error));
    }
    $conn->close();
} else {
    header("Location: gestion_usuarios.php?status=error&message=Acceso no válido.");
}
exit();
?>