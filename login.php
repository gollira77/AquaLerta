<?php
include 'config.php';
include 'funciones.php';

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lógica de inicio de sesión
if (isset($_POST['login'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['usuario'] = $user;
        // La función redireccionSegunTipo debe estar definida en funciones.php
        redireccionSegunTipo($user['tipo_usuario']);
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>Credenciales incorrectas</div>"; // Agregado text-center para centrar el mensaje de error
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<style>
    /* Variables CSS para colores */
    :root {
        --primary-dark-blue: #1a237e; /* Azul oscuro del navbar y fondo */
        --accent-blue: #4285f4; /* Azul de botones */
        --card-bg-color: #ffffff; /* Fondo blanco de la tarjeta */
        --text-color: #333333;
        --link-color: #007bff; /* Color para enlaces */
    }

    /* Estilos del Body para centrar el contenido y dar el color de fondo */
    body {
        background-color: var(--primary-dark-blue); /* Fondo azul oscuro */
        display: flex; /* Habilitar Flexbox */
        justify-content: center; /* Centrar horizontalmente */
        align-items: center; /* Centrar verticalmente */
        min-height: 100vh; /* Ocupar el 100% del alto de la ventana */
        margin: 0; /* Eliminar margen por defecto del body */
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Fuente consistente */
        color: var(--text-color);
    }

    /* Ocultar el navbar en la página de login, ya que el diseño es una tarjeta centrada */
    .navbar {
        display: none !important;
    }

    /* Estilos de la tarjeta de login */
    .login-card {
        background-color: var(--card-bg-color);
        border-radius: 12px; /* Bordes redondeados */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra suave */
        padding: 40px; /* Espaciado interno */
        text-align: center; /* Centrar el texto dentro de la tarjeta */
        width: 90%; /* Ajuste a un porcentaje del ancho de la ventana */
        max-width: 450px; /* Aumentado el ancho máximo para inputs más largos en escritorio */
        animation: fadeIn 0.5s ease-out; /* Animación de aparición */
    }

    /* Media query para pantallas más grandes (ajustar max-width del login-card) */
    @media (min-width: 768px) {
        .login-card {
            max-width: 550px; /* Aumenta el ancho máximo de la tarjeta para pantallas más grandes */
            padding: 50px; /* Un poco más de padding en pantallas grandes */
        }
    }


    /* Animación de aparición */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-card h2 {
        font-weight: 700;
        color: var(--primary-dark-blue);
        margin-bottom: 25px;
        font-size: 1.8rem;
    }

    .login-card .form-label { /* Usar form-label para el texto de las etiquetas */
        display: block;
        text-align: left; /* Alinear etiquetas a la izquierda */
        margin-bottom: 5px;
        font-weight: 500;
        color: #555;
    }

    .login-card .form-control {
        border-radius: 8px; /* Bordes redondeados para inputs */
        padding: 12px 15px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        /* Asegurarse de que ocupe todo el ancho disponible del padre */
        width: 100%; 
    }

    .login-card .form-control:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
    }

    .login-card .btn-primary {
        background-color: var(--accent-blue);
        border-color: var(--accent-blue);
        font-weight: 600;
        padding: 12px 25px;
        border-radius: 25px; /* Botón más redondeado */
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        width: 100%; /* Botón de ancho completo */
        margin-top: 25px;
    }

    .login-card .btn-primary:hover {
        background-color: #357ae8;
        border-color: #357ae8;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .login-card .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.5);
    }

    .login-card .register-link {
        margin-top: 20px;
        font-size: 0.95rem;
    }

    .login-card .register-link a {
        color: var(--link-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .login-card .register-link a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Mensajes de alerta/error */
    .alert {
        margin-top: 20px;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

</style>
<title>AquaLerta - Iniciar Sesión</title>
</head>
<body>
    <?php // include 'includes/navbar.php'; ?> 
    
    <div class="login-card">
        <h2 class="mb-4">AquaLerta</h2> <p class="text-muted mb-4">Bienvenido de nuevo</p> <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="usuario@ejemplo.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="**********" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Iniciar sesión</button>
        </form>
        <div class="register-link">
            ¿No tenés una cuenta? <a href="register.php">Registrate aquí</a>
        </div>
    </div>
</body>
</html>