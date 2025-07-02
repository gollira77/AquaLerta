<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AquaLerta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/estilo.css" rel="stylesheet">
</head>
<body class="bg-slate">
  <!-- Navbar solo con login y registro -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container px-3">
      <a class="navbar-brand fw-bold" href="index.php">AquaLerta</a>
      <div class="d-flex gap-2">
        <a href="login.php" class="btn btn-light text-primary fw-semibold px-3 py-1 rounded-3">Iniciar sesión</a>
        <a href="register.php" class="btn btn-outline-light fw-semibold px-3 py-1 rounded-3">Registrarse</a>
      </div>
    </div>
  </nav>
  <!-- Bienvenida -->
  <main class="text-center py-5 px-2">
    <div class="d-flex justify-content-center align-items-center" style="height: 260px;">
      <div>
        <h1 class="fw-bold mb-2 text-primary display-5">Bienvenido a AquaLerta</h1>
        <p class="lead text-secondary mb-0">Tu plataforma de alertas y prevención ante inundaciones en Formosa</p>
      </div>
    </div>
    <div class="bg-info bg-opacity-10 rounded-4 shadow-sm mt-4 mx-auto p-4" style="max-width: 580px;">
      <h2 class="fs-4 fw-semibold text-primary mb-1">¿Cómo te ayudamos?</h2>
      <p class="text-secondary mb-0">Recibí notificaciones anticipadas, aprendé cómo actuar en casos de emergencia y conectate con quienes pueden asistirte en tiempo real.</p>
    </div>
  </main>
</body>
</html>