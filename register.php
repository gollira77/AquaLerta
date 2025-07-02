<?php
require_once 'config.php';

// Asegúrate de que las sesiones estén iniciadas si es necesario (generalmente en config.php)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Obtener roles de la base de datos dinámicamente
$stmt_roles = $pdo->query("SELECT id_tipo_usuario, tipo FROM tipos_usuarios ORDER BY tipo ASC");
$tipos_usuarios_db = $stmt_roles->fetchAll(PDO::FETCH_ASSOC);

$roles_map = [];     // Para el PHP match: ['Ciudadano' => 1, ...]
$roles_for_js = [];  // Para el JavaScript: ['Ciudadano' => 'Ciudadano', 'Rescatista' => 'Rescatista', ...]

foreach ($tipos_usuarios_db as $rol) {
    $roles_map[$rol['tipo']] = $rol['id_tipo_usuario'];
    $roles_for_js[$rol['tipo']] = $rol['tipo']; // La clave y el valor son el nombre del rol
}

// 1. Obtener barrios de la base de datos dinámicamente
$stmt_barrios = $pdo->query("SELECT id_barrio, barrio FROM barrios ORDER BY barrio ASC");
$barrios_db = $stmt_barrios->fetchAll(PDO::FETCH_ASSOC);


// Inicializar variables para el formulario
$selected_role_name = 'Ciudadano'; // Valor por defecto
if (isset($_POST['role']) && array_key_exists($_POST['role'], $roles_map)) { // Usar roles_map para validar la existencia
    $selected_role_name = $_POST['role'];
} else if (isset($tipos_usuarios_db[0]['tipo'])) {
    $selected_role_name = $tipos_usuarios_db[0]['tipo']; // Si no hay post, toma el primer rol de la DB
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger todos los datos del formulario
    $nombre = trim($_POST['nombre_usuario']); // Cambiado de 'usuario' a 'nombre_usuario'
    $apellido = trim($_POST['apellido'] ?? ''); // Opcional
    $email = trim($_POST['email']); // Nuevo campo, ahora obligatorio
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = trim($_POST['telefono'] ?? ''); // Opcional
    $id_barrio = intval($_POST['id_barrio']); // Nuevo campo, ahora obtenido del select
    $fecha_registro = date('Y-m-d');
    
    // Obtener el ID de tipo de usuario basado en el nombre de rol seleccionado del POST
    $id_tipo_usuario = $roles_map[$selected_role_name] ?? $roles_map['Ciudadano']; // Si no encuentra, por defecto Ciudadano

    // 3. Validar si el email ya existe (cambiado de 'nombre' a 'email')
    $verificar_email = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $verificar_email->execute([$email]);
    
    if ($verificar_email->fetchColumn() > 0) {
        $error = "Ese email ya está registrado. Por favor, usa otro o inicia sesión.";
    } else {
        // Validar que el barrio seleccionado sea válido
        $barrio_valido = false;
        foreach ($barrios_db as $b) {
            if ($b['id_barrio'] == $id_barrio) {
                $barrio_valido = true;
                break;
            }
        }
        if (!$barrio_valido && !empty($barrios_db)) { // Si el ID no es válido y hay barrios
            $error = "Por favor, selecciona un barrio válido.";
        } else if (empty($barrios_db)) { // Si no hay barrios en la DB
             $error = "No hay barrios disponibles para el registro. Contacta al administrador.";
        } else {
            // 2. Insertar todos los campos en la tabla usuarios
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password, telefono, id_barrio, fecha_registro, id_tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$nombre, $apellido, $email, $password, $telefono, $id_barrio, $fecha_registro, $id_tipo_usuario]);
                header('Location: login.php');
                exit;
            } catch (PDOException $e) { // Usar PDOException para errores de DB
                $error = "Error al registrar usuario: " . htmlspecialchars($e->getMessage());
                // Considera loggear $e->getMessage() para depuración en producción
            }
        }
    }
}

// Para el badge inicial y la selección del dropdown si no hay POST (o si hay error)
$current_badge_text = $roles_for_js[$selected_role_name] ?? 'Ciudadano';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Registrarse - AquaLerta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/estilo.css" rel="stylesheet">
</head>
<body class="bg-gradient">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="bg-white p-5 rounded-4 shadow-lg w-100" style="max-width: 450px;">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary">AquaLerta</h1>
                <p class="text-secondary small">
                    Creá tu cuenta como
                    <span class="badge bg-primary-subtle text-primary" id="rolBadge">
                        <?= htmlspecialchars($current_badge_text) ?>
                    </span>
                </p>
            </div>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off">
                <label class="form-label text-secondary small mb-1">Tipo de cuenta</label>
                <select name="role" id="role" class="form-select mb-3" required>
                    <?php if (empty($tipos_usuarios_db)): ?>
                        <option value="">No hay tipos de usuario disponibles</option>
                    <?php else: ?>
                        <?php foreach($tipos_usuarios_db as $rol): ?>
                            <option value="<?= htmlspecialchars($rol['tipo']) ?>" 
                                <?= $selected_role_name == $rol['tipo'] ? "selected" : "" ?>>
                                <?= htmlspecialchars($rol['tipo']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <label class="form-label text-secondary small mb-1">Nombre</label>
                <input type="text" name="nombre_usuario" placeholder="Tu nombre" class="form-control mb-3" required value="<?= htmlspecialchars($_POST['nombre_usuario'] ?? '') ?>">

                <label class="form-label text-secondary small mb-1">Apellido (Opcional)</label>
                <input type="text" name="apellido" placeholder="Tu apellido" class="form-control mb-3" value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">

                <label class="form-label text-secondary small mb-1">Email</label>
                <input type="email" name="email" placeholder="tu@email.com" class="form-control mb-3" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

                <label class="form-label text-secondary small mb-1">Teléfono (Opcional)</label>
                <input type="text" name="telefono" placeholder="Tu teléfono" class="form-control mb-3" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">

                <label class="form-label text-secondary small mb-1">Barrio</label>
                <select name="id_barrio" id="id_barrio" class="form-select mb-3" required>
                    <?php if (empty($barrios_db)): ?>
                        <option value="">No hay barrios disponibles</option>
                    <?php else: ?>
                        <option value="">Selecciona tu barrio</option>
                        <?php foreach($barrios_db as $barrio): ?>
                            <option value="<?= $barrio['id_barrio'] ?>"
                                <?= (isset($_POST['id_barrio']) && $_POST['id_barrio'] == $barrio['id_barrio']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($barrio['barrio']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <label class="form-label text-secondary small mb-1">Contraseña</label>
                <input type="password" name="password" placeholder="*******" class="form-control mb-4" required>
                
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3">Registrarse</button>
            </form>
            <p class="text-center text-secondary small mt-4 mb-0">
                ¿Ya tenés cuenta?
                <a href="login.php" class="text-primary fw-medium">Iniciar sesión</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectRole = document.getElementById('role');
            const badge = document.getElementById('rolBadge');
            
            // Roles en JavaScript actualizados para coincidir con la DB
            const rolesDisplayMap = {
                <?php
                // Asegurarse de que $roles_for_js esté definido y sea un array
                if (!empty($roles_for_js)) {
                    $js_pairs = [];
                    foreach ($roles_for_js as $key => $value) {
                        $js_pairs[] = "'" . addslashes($key) . "': '" . addslashes($value) . "'";
                    }
                    echo implode(",\n", $js_pairs);
                }
                ?>
            };
            
            selectRole.addEventListener('change', function() {
                // Asegurarse de que la clave exista antes de acceder
                if (rolesDisplayMap.hasOwnProperty(selectRole.value)) {
                    badge.textContent = rolesDisplayMap[selectRole.value];
                } else {
                    // Mostrar el texto de la opción seleccionada si la clave no existe en el mapa JS
                    badge.textContent = selectRole.options[selectRole.selectedIndex].text;
                }
            });
        });
    </script>
</body>
</html>