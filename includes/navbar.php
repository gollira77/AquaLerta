<?php
// Obtener cantidad de alertas pendientes (solo para usuarios)
$alertas_pendientes = 0;
if (isset($_SESSION['role']) && $_SESSION['role'] == 'usuario') {
    $stmt_nav = $pdo->query("SELECT COUNT(*) as c FROM alertas");
    $alertas_pendientes = $stmt_nav->fetch()['c'];
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container px-3">
    <a class="navbar-brand fw-bold" href="home.php">AquaLerta</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-grow-0" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <?php if(isset($_SESSION['role'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="home.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link position-relative" href="home.php#alertas">
              <i class="bi bi-bell-fill"></i>
              Alertas
              <?php if($alertas_pendientes > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <?= $alertas_pendientes ?>
                </span>
              <?php endif; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="educacion.php">Educación</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-light text-primary fw-semibold ms-2 px-3 py-1 rounded-3" href="logout.php">Cerrar sesión</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>