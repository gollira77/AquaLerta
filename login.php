<?php include 'config.php'; include 'funciones.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<title>Login</title>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-5">
    <h2>Iniciar Sesión</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-success">Entrar</button>
    </form>
    </div>
    </body>
    </html>
    <?php
    if (isset($_POST['login'])) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['usuario'] = $user;
            redireccionSegunTipo($user['tipo_usuario']);
        } else {
            echo "<div class='alert alert-danger'>Credenciales incorrectas</div>";
        }
    }
    ?>