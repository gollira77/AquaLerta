<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['role'] = $user['role'];
        header('Location: home.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar Sesión - AquaLerta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/estilo.css" rel="stylesheet">
</head>
<body class="bg-gradient">

  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="bg-white p-5 rounded-4 shadow-lg w-100" style="max-width: 400px;">
      <div class="text-center mb-4">
        <h1 class="fw-bold text-primary">AquaLerta</h1>
        <p class="text-secondary small">Bienvenido de nuevo</p>
      </div>
      <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST" autocomplete="off">
        <label class="form-label text-secondary small mb-1">Usuario</label>
        <input type="text" name="usuario" placeholder="Tu usuario" class="form-control mb-3" required>
        <label class="form-label text-secondary small mb-1">Contraseña</label>
        <input type="password" name="password" placeholder="••••••••" class="form-control mb-4" required>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3">Iniciar sesión</button>
      </form>
      <p class="text-center text-secondary small mt-4 mb-0">
        ¿No tenés una cuenta?
        <a href="register.php" class="text-primary fw-medium">Registrate aquí</a>
      </p>
    </div>
  </div>
</body>
</html>