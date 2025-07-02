<?php
require_once 'config.php';

$roles = [
    'usuario' => 'Usuario',
    'rescatista' => 'Rescatista',
    'medio' => 'Medio de Comunicación'
];

$selected_role = $_POST['role'] ?? 'usuario';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, role) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$usuario, $password, $role]);
        header('Location: login.php');
        exit;
    } catch (Exception $e) {
        $error = "Error al registrar usuario";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registrarse - AquaLerta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/estilo.css" rel="stylesheet">
  <script>
    // Cambia el badge según la selección del usuario
    document.addEventListener('DOMContentLoaded', function() {
      const selectRole = document.getElementById('role');
      const badge = document.getElementById('rolBadge');
      const roles = {
        usuario: "Usuario",
        rescatista: "Rescatista",
        medio: "Medio de Comunicación"
      };
      selectRole.addEventListener('change', function() {
        badge.textContent = roles[selectRole.value];
      });
    });
  </script>
</head>
<body class="bg-gradient">
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="bg-white p-5 rounded-4 shadow-lg w-100" style="max-width: 400px;">
      <div class="text-center mb-4">
        <h1 class="fw-bold text-primary">AquaLerta</h1>
        <p class="text-secondary small">
          Creás tu cuenta como
          <span class="badge bg-primary-subtle text-primary" id="rolBadge">
            <?= $roles[$selected_role] ?>
          </span>
        </p>
      </div>
      <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST" autocomplete="off">
        <label class="form-label text-secondary small mb-1">Tipo de cuenta</label>
        <select name="role" id="role" class="form-select mb-3" required>
          <?php foreach($roles as $key => $nombre): ?>
            <option value="<?= $key ?>" <?= $selected_role == $key ? "selected" : "" ?>>
              <?= $nombre ?>
            </option>
          <?php endforeach; ?>
        </select>
        <label class="form-label text-secondary small mb-1">Usuario</label>
        <input type="text" name="usuario" placeholder="Tu usuario" class="form-control mb-3" required>
        <label class="form-label text-secondary small mb-1">Contraseña</label>
        <input type="password" name="password" placeholder="••••••••" class="form-control mb-4" required>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3">Registrarse</button>
      </form>
      <p class="text-center text-secondary small mt-4 mb-0">
        ¿Ya tenés cuenta?
        <a href="login.php" class="text-primary fw-medium">Iniciar sesión</a>
      </p>
    </div>
  </div>
</body>
</html>