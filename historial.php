<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

$usuario_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM alertas WHERE usuario_id = ? ORDER BY fecha DESC");
$stmt->execute([$usuario_id]);
$alertas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Historial de reportes</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container">
    <h2>Historial de Alertas</h2>
    <ul>
    <?php foreach($alertas as $a): ?>
        <li>
            <b><?= htmlspecialchars($a['titulo']) ?></b> - <?= htmlspecialchars($a['descripcion']) ?>
            <span>(<?= $a['fecha'] ?>)</span>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
</body>
</html>