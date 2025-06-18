<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<title>Recuperar contraseña</title>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-5">
    <h2>Recuperar Contraseña</h2>
    <form method="POST">
        <div class="mb-3">
        <label>Email registrado</label>
        <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" name="enviar" class="btn btn-primary">Enviar correo</button>
    </form>
    </div>
    </body>
    </html>
    <?php
    if (isset($_POST['enviar'])) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch();
        if ($user) {
            $token = bin2hex(random_bytes(16));
            // Aquí deberías guardar el token y enviar el correo real con un link tipo:
            // recuperar_token.php?token=xyz
            echo "<div class='alert alert-info'>Se ha enviado un enlace a su correo (simulado).</div>";
        } else {
            echo "<div class='alert alert-danger'>Correo no registrado.</div>";
        }
    }
    ?>
