<?php
include 'config.php';
include 'funciones.php';

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lógica de registro
if (isset($_POST['registrar'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password, telefono, id_barrio, fecha_registro, tipo_usuario)
                                VALUES (?, ?, ?, ?, ?, ?, NOW(), 'ciudadano')");
        $stmt->execute([
            $_POST['nombre'], $_POST['apellido'], $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['telefono'], $_POST['id_barrio']
        ]);
        echo "<div class='alert alert-success text-center mt-3'>Usuario registrado exitosamente. Ya puedes <a href='login.php'>iniciar sesión</a>.</div>";
    } catch (PDOException $e) {
        // Manejo de errores, por ejemplo, si el email ya existe (asumiendo columna email es UNIQUE)
        if ($e->getCode() == '23000') { // Código de error para violación de unicidad
            echo "<div class='alert alert-danger text-center mt-3'>El email ya está registrado. Por favor, usa otro.</div>";
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>Error al registrar usuario: " . $e->getMessage() . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<title>AquaLerta - Registro</title>
<style>
    /* Variables CSS para colores (igual que en login.php) */
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
        padding: 20px 0; /* Añadir padding vertical para evitar que la tarjeta toque los bordes en pantallas pequeñas */
    }

    /* Ocultar el navbar en la página de registro, ya que el diseño es una tarjeta centrada */
    .navbar {
        display: none !important;
    }

    /* Estilos de la tarjeta de registro */
    .register-card {
        background-color: var(--card-bg-color);
        border-radius: 12px; /* Bordes redondeados */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra suave */
        padding: 30px; /* Espaciado interno, ajustado para más campos */
        text-align: center; /* Centrar el texto dentro de la tarjeta */
        width: 90%; /* Ajuste a un porcentaje del ancho de la ventana */
        max-width: 500px; /* Ancho máximo de la tarjeta de registro (un poco más grande que login) */
        animation: fadeIn 0.5s ease-out; /* Animación de aparición */
    }

    /* Media query para pantallas más grandes (ajustar max-width del register-card) */
    @media (min-width: 768px) {
        .register-card {
            max-width: 600px; /* Aumenta el ancho máximo de la tarjeta para pantallas más grandes */
            padding: 40px; /* Un poco más de padding en pantallas grandes */
        }
    }

    /* Animación de aparición */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .register-card h2 {
        font-weight: 700;
        color: var(--primary-dark-blue);
        margin-bottom: 25px;
        font-size: 1.8rem;
    }

    .register-card .form-label {
        display: block;
        text-align: left; /* Alinear etiquetas a la izquierda */
        margin-bottom: 5px;
        font-weight: 500;
        color: #555;
    }

    .register-card .form-control {
        border-radius: 8px; /* Bordes redondeados para inputs */
        padding: 12px 15px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        width: 100%; /* Ocupar todo el ancho disponible del padre */
    }

    .register-card .form-control:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
    }

    .register-card .btn-primary {
        background-color: var(--accent-blue);
        border-color: var(--accent-blue);
        font-weight: 600;
        padding: 12px 25px;
        border-radius: 25px; /* Botón más redondeado */
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        width: 100%; /* Botón de ancho completo */
        margin-top: 25px;
    }

    .register-card .btn-primary:hover {
        background-color: #357ae8;
        border-color: #357ae8;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .register-card .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.5);
    }

    .register-card .login-link { /* Nuevo enlace para ir al login */
        margin-top: 20px;
        font-size: 0.95rem;
    }

    .register-card .login-link a {
        color: var(--link-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .register-card .login-link a:hover {
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
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

</style>
</head>
<body>
    <?php // include 'includes/navbar.php'; ?> 
    
    <div class="register-card">
        <h2 class="mb-4">Registro de Usuario</h2>
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" id="apellido" name="apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control">
            </div>
            <div class="mb-3">
                <label for="id_barrio" class="form-label">Barrio</label>
                <select id="id_barrio" name="id_barrio" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php
                    // Asegúrate de que $pdo está disponible (desde config.php)
                    $barrios = $pdo->query("SELECT id_barrio, barrio FROM barrios LIMIT 10");
                    while ($b = $barrios->fetch()) {
                        echo "<option value='{$b['id_barrio']}'>{$b['barrio']}</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="latitud" id="latitud">
            <input type="hidden" name="longitud" id="longitud">
            <button type="submit" name="registrar" class="btn btn-primary">Registrarse</button>
        </form>
        <div class="login-link">
            ¿Ya tenés una cuenta? <a href="login.php">Iniciar sesión</a>
        </div>
    </div>
    
    <script>
    // Script para obtener la geolocalización (sin cambios)
    if (navigator.geolocation) { // Verificar si el navegador soporta geolocalización
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('latitud').value = pos.coords.latitude;
            document.getElementById('longitud').value = pos.coords.longitude;
        }, function(error) {
            // Manejar errores de geolocalización, por ejemplo:
            console.warn('ERROR(' + error.code + '): ' + error.message);
            // Puedes mostrar un mensaje al usuario o no hacer nada si la ubicación no es crucial
        });
    } else {
        console.log("Tu navegador no soporta la geolocalización.");
    }
    </script>
</body>
</html>