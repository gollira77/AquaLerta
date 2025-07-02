<?php
require_once 'funciones.php';
redirect_if_not_logged_in();
if (get_user_role() != 'admin') die('Acceso denegado');

// Borrar alerta
if (isset($_GET['borrar_alerta']) && is_numeric($_GET['borrar_alerta'])) {
    $id = intval($_GET['borrar_alerta']);
    $pdo->prepare("DELETE FROM alertas WHERE id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM rescatistas_alerta WHERE alerta_id=?")->execute([$id]);
    header('Location: admin.php');
    exit;
}

// Borrar usuario
if (isset($_GET['borrar_usuario']) && is_numeric($_GET['borrar_usuario'])) {
    $id = intval($_GET['borrar_usuario']);
    $pdo->prepare("DELETE FROM usuarios WHERE id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM rescatistas_alerta WHERE rescatista_id=?")->execute([$id]);
    header('Location: admin.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM alertas ORDER BY fecha DESC");
$alertas = $stmt->fetchAll();

$stmt2 = $pdo->query("SELECT * FROM usuarios ORDER BY role, usuario");
$usuarios = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/estilo.css">
    <title>Panel Admin</title>
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
                    <?php
                    foreach($alertas as $a){
                        echo "<div class='alerta-card mb-3 p-3 rounded bg-white shadow-sm'>";
                        echo "<h5 class='text-main'>" . htmlspecialchars($a['titulo']) . "</h5>";
                        echo "<p>" . htmlspecialchars($a['descripcion']) . "</p>";
                        echo "<small class='text-muted'>Fecha: " . $a['fecha'] . "</small><br>";
                        echo "<a href='admin.php?borrar_alerta=" . $a['id'] . "' class='btn btn-danger btn-sm mt-2'>Eliminar</a>";
                        echo "</div>";
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
                    <ul class="list-group">
                    <?php foreach($usuarios as $u): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($u['usuario']) ?> <span class="badge bg-main"><?= $u['role'] ?></span>
                            <a href="admin.php?borrar_usuario=<?= $u['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </li>
                    <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>