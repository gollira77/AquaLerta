<?php
session_start();

function verificarSesion() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit();
    }
}

function redireccionSegunTipo($tipo_usuario) {
    switch ($tipo_usuario) {
        case 'ciudadano': header('Location: alertas.php'); break;
        case 'rescatista': header('Location: reportar.php'); break;
        case 'autoridad': header('Location: admin.php'); break;
        case 'ong': header('Location: educacion.php'); break;
        default: header('Location: index.php');
    }
}
?>