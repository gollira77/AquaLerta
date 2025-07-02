<?php
require_once 'funciones.php';
redirect_if_not_logged_in();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/estilo.css">
    <title>Módulo educativo</title>
</head>
<body class="bg-main">
<?php include 'includes/navbar.php'; ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-celeste text-white">
                    <h3>Módulo Educativo</h3>
                </div>
                <div class="card-body">
                    <p class="mb-3">Aquí tienes recursos sobre prevención y respuesta a emergencias. Explora y aprende.</p>
                    <ul>
                        <li><a href="https://www.cruzroja.org.ar/" target="_blank">Cruz Roja Argentina</a></li>
                        <li><a href="https://www.argentina.gob.ar/salud" target="_blank">Ministerio de Salud</a></li>
                        <li><a href="https://www.smn.gob.ar/" target="_blank">Servicio Meteorológico Nacional</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>