<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

if (get_user_role() != 'admin') die('Acceso denegado');

$mensaje = '';
if (isset($_SESSION['mensaje_admin'])) {
    $mensaje = $_SESSION['mensaje_admin'];
    unset($_SESSION['mensaje_admin']); 
}

if (isset($_GET['finalizar_alerta']) && is_numeric($_GET['finalizar_alerta'])) {
    $id_alerta_a_finalizar = intval($_GET['finalizar_alerta']);
    try {
        $stmt = $pdo->prepare("UPDATE alertas SET activa = 0 WHERE id_alerta = ?");
        $stmt->execute([$id_alerta_a_finalizar]);
        $_SESSION['mensaje_admin'] = "<div class='alert alert-success'>Alerta finalizada correctamente.</div>";
    } catch (PDOException $e) {
        $_SESSION['mensaje_admin'] = "<div class='alert alert-danger'>Error al finalizar la alerta: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    header('Location: admin.php');
    exit;
}

if (isset($_GET['borrar_usuario']) && is_numeric($_GET['borrar_usuario'])) {
    $id_usuario_a_borrar = intval($_GET['borrar_usuario']);
    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id_usuario_a_borrar]);
        $_SESSION['mensaje_admin'] = "<div class='alert alert-success'>Usuario y sus datos relacionados (alertas recibidas, historial, eventos) eliminados correctamente.</div>";
    } catch (PDOException $e) {
        $_SESSION['mensaje_admin'] = "<div class='alert alert-danger'>Error al eliminar el usuario: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    header('Location: admin.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM alertas ORDER BY fecha_hora DESC");
$alertas = $stmt->fetchAll();

$stmt2 = $pdo->query("SELECT u.id_usuario, u.nombre, tu.tipo 
                    FROM usuarios u 
                    JOIN tipos_usuarios tu ON u.id_tipo_usuario = tu.id_tipo_usuario 
                    ORDER BY tu.tipo, u.nombre");
$usuarios = $stmt2->fetchAll();   
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/estilo.css">
    <title>Panel Admin - AquaLerta</title>
</head>
<body class="bg-main">
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-celeste text-white">
                    <h4>Gestionar Alertas</h4>
                </div>
                <div class="card-body">
                    <?= $mensaje ?> <?php
                    if (empty($alertas)) {
                        echo "<p class='text-secondary'>No hay alertas registradas.</p>";
                    } else {
                        foreach($alertas as $a){
                            $estado_alerta = ($a['activa'] == 1) ? "<span class='badge bg-success'>Activa</span>" : "<span class='badge bg-secondary'>Finalizada</span>";

                            echo "<div class='alerta-card mb-3 p-3 rounded bg-white shadow-sm'>";
                            echo "<h5 class='text-main'>" . htmlspecialchars($a['titulo']) . " " . $estado_alerta . "</h5>";
                            echo "<p>" . htmlspecialchars($a['descripcion']) . "</p>";
                            echo "<small class='text-muted'>Fecha: " . $a['fecha_hora'] . "</small><br>";
                            if ($a['activa'] == 1) { 
                                echo "<a href='admin.php?finalizar_alerta=" . $a['id_alerta'] . "' class='btn btn-warning btn-sm mt-2' onclick=\"return confirm('¿Estás seguro de que quieres FINALIZAR esta alerta?');\">Finalizar Alerta</a>";
                            } else {
                                echo "<button class='btn btn-secondary btn-sm mt-2' disabled>Alerta Finalizada</button>";
                            }
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-celeste text-white">
                    <h4>Gestionar Usuarios</h4>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['mensaje_admin'])) {
                        echo $_SESSION['mensaje_admin'];
                        unset($_SESSION['mensaje_admin']);
                    }
                    if (empty($usuarios)) {
                        echo "<p class='text-secondary'>No hay usuarios registrados.</p>";
                    } else {
                        echo "<ul class='list-group'>";
                        foreach($usuarios as $u):
                    ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($u['nombre']) ?> <span class="badge bg-main"><?= htmlspecialchars($u['tipo']) ?></span>
                                <a href="admin.php?borrar_usuario=<?= $u['id_usuario'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar a este usuario y todos sus datos relacionados (alertas recibidas, historial, eventos)?');">Eliminar</a>
                            </li>
                    <?php 
                        endforeach;
                        echo "</ul>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>