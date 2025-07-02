<?php
require_once 'funciones.php';
redirect_if_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $usuario_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("INSERT INTO alertas (titulo, descripcion, usuario_id, fecha) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$titulo, $descripcion, $usuario_id]);
    header('Location: historial.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reportar alerta</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container">
    <h2>Reportar alerta</h2>
    <form method="POST">
        <input class="form-control" type="text" name="titulo" placeholder="Título" required><br>
        <textarea class="form-control" name="descripcion" placeholder="Descripción" required></textarea><br>
        <button class="btn btn-primary" type="submit">Enviar</button>
    </form>
</div>
</body>
</html>