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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-main">
<?php include 'includes/navbar.php'; ?>
<div class="main-content-wrapper">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h3 class="text-primary">Antes de una Inundación</h3>
                <hr>
                <div class="row mb-4 justify-content-center"> 
                    <div class="col-md-8 col-lg-6 col-12"> 
                        <img src="assets/img/antes1.png" class="img-fluid rounded shadow-sm mb-3" alt="Preparación para inundación - Kit de emergencia">
                        <img src="assets/img/antes2.png" class="img-fluid rounded shadow-sm mb-3" alt="Preparación para inundación - Documentos seguros">
                        <img src="assets/img/antes3.png" class="img-fluid rounded shadow-sm mb-3" alt="Preparación para inundación - Elevando objetos">
                    </div>
                </div>
                <ul>
                    <li>Informate sobre si tu zona es propensa a inundaciones.</li>
                    <li>Prepará un kit de emergencia con agua, alimentos no perecederos, linterna, radio a baterías y botiquín.</li>
                    <li>Guardá documentos importantes en bolsas plásticas o en la nube.</li>
                    <li>Elevá electrodomésticos y objetos de valor si vivís en planta baja.</li>
                    <li>Tené a mano números de emergencia y ubicaciones de centros de evacuación.</li>
                </ul>

                <h3 class="text-primary mt-4">EN CASA</h3>
                <hr>
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-8 col-lg-6 col-12">
                        <img src="assets/images/educacion/casa_1.jpg" class="img-fluid rounded shadow-sm mb-3" alt="En casa - Revise el estado">
                        <img src="assets/images/educacion/casa_2.jpg" class="img-fluid rounded shadow-sm mb-3" alt="En casa - Tenga preparado">
                        <img src="assets/images/educacion/casa_3.jpg" class="img-fluid rounded shadow-sm mb-3" alt="En casa - Cierre y asegure">
                        <img src="assets/images/educacion/casa_4.jpg" class="img-fluid rounded shadow-sm mb-3" alt="En casa - Desenchufe los aparatos">
                        <img src="assets/images/educacion/casa_5.jpg" class="img-fluid rounded shadow-sm mb-3" alt="En casa - En caso de inundación">
                    </div>
                </div>
                <ul>
                    <li>Revise el estado de los tejados, azoteas, así como desagües y bajantes.</li>
                    <li>Tenga preparado linterna o velas, agua potable, botiquín de primeros auxilios.</li>
                    <li>Cierre y asegure las ventanas y puertas para impedir la entrada del agua y rayos.</li>
                    <li>Desenchufe los aparatos eléctricos para evitar que sean dañados o provoquen descargas eléctricas.</li>
                    <li>En caso de inundación abandone los sótanos y la planta baja y desconecte el interruptor general de electricidad.</li>
                </ul>

                <h3 class="text-primary mt-4">Durante una Inundación</h3>
                <hr>
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-8 col-lg-6 col-12">
                        <img src="assets/images/educacion/durante_1.jpg" class="img-fluid rounded shadow-sm mb-3" alt="Durante la inundación - No conducir">
                        <img src="assets/images/educacion/durante_2.jpg" class="img-fluid rounded shadow-sm mb-3" alt="Durante la inundación - Desconectar electricidad">
                        <img src="assets/images/educacion/durante_3.jpg" class="img-fluid rounded shadow-sm mb-3" alt="Durante la inundación - Buscando refugio">
                    </div>
                </div>
                <ul>
                    <li>Mantené la calma y seguí las instrucciones de las autoridades.</li>
                    <li>No camines ni conduzcas por calles inundadas.</li>
                    <li>Desconectá la energía eléctrica si hay peligro de que el agua entre a tu casa.</li>
                    <li>Dirigite a zonas altas o centros de evacuación si es necesario.</li>
                    <li>Escuchá la radio o seguí canales oficiales para actualizaciones.</li>
                </ul>

                <h3 class="text-primary mt-4">Después de una Inundación</h3>
                <hr>
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-8 col-lg-6 col-12">
                        <img src="assets/images/educacion/despues_1.jpg" class="img-fluid rounded shadow-sm mb-3" alt="Después de la inundación - Regreso seguro a casa">
                        <img src="assets/images/educacion/despues_2.jpg" class="img-fluid rounded shadow-sm mb-3" alt="Después de la inundación - Limpieza y desinfección">
                        <img src="assets/images/educacion/despues_3.jpg" class="img-fluid rounded shadow-sm mb-3" alt="Después de la inundación - Revisión médica">
                    </div>
                </div>
                <ul>
                    <li>Volvé a tu casa solo cuando las autoridades lo indiquen como seguro.</li>
                    <li>No consumas alimentos ni agua que hayan estado en contacto con el agua de la inundación.</li>
                    <li>Desinfectá las superficies y ventilá bien tu casa.</li>
                    <li>Documentá los daños para posibles reclamos o ayuda estatal.</li>
                    <li>Prestá atención a síntomas de enfermedades y buscá ayuda médica si es necesario.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
</html>