<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaLerta - Educación Completa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    /* Colores y Variables */
    :root {
        --primary-dark-blue: #1a237e;
        --accent-blue: #4285f4;
        --light-text: #f0f4f8;
        --hover-underline-color: #f0f4f8;
        --body-bg-color: #eef2f6;
        --card-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    /* Estilos Generales del Cuerpo */
    body {
        background-color: var(--body-bg-color);
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        padding-top: 70px;
        margin: 0;
        box-sizing: border-box;
    }

    /* Contenedor principal del contenido */
    .main-content-wrapper {
        padding-bottom: 30px;
    }

    /* Estilos de Encabezados (h3) */
    h3 {
        font-weight: 700;
        color: #0056b3;
        margin-bottom: 1rem;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
    }

    /* Estilos de Tarjetas (Card) */
    .card {
        box-shadow: var(--card-shadow);
        border-radius: 12px;
        border: 1px solid #dee2e6;
        overflow: hidden;
    }

    .card-body {
        padding: 2.5rem;
    }

    /* Estilos de Listas dentro de la Tarjeta */
    .card-body ul {
        list-style: none;
        padding-left: 0;
        margin-top: 1.5rem;
    }

    .card-body ul li {
        margin-bottom: 1rem;
        padding-left: 1.8rem;
        position: relative;
        font-size: 1.05rem;
        color: #444;
    }

    .card-body ul li::before {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: #28a745;
        position: absolute;
        left: 0;
        top: 0;
    }
    .card-body ul li:not([class*="fa"])::before {
        content: '\2714';
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #28a745;
    }

    /* ESTILOS DEL NAVBAR (SIN CAMBIOS) */
    .navbar {
        background-color: var(--primary-dark-blue) !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding-top: 0.8rem;
        padding-bottom: 0.8rem;
        z-index: 1030;
    }

    .bg-primary-dark {
        background-color: var(--primary-dark-blue) !important;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.8rem;
        color: var(--light-text) !important;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    .nav-link {
        font-weight: 500;
        color: var(--light-text) !important;
        margin-right: 25px;
        position: relative;
        padding-bottom: 5px;
        transition: color 0.3s ease, transform 0.2s ease;
    }

    .nav-link:hover {
        color: var(--accent-blue) !important;
        transform: translateY(-2px);
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background-color: var(--hover-underline-color);
        transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    .navbar .btn-primary {
        background-color: var(--accent-blue);
        border-color: var(--accent-blue);
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        margin-left: 10px;
    }

    .navbar .btn-primary:hover {
        background-color: #357ae8;
        border-color: #357ae8;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .navbar .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.5);
    }

    @media (max-width: 991.98px) {
        .navbar-collapse .nav-item .btn-primary {
            margin-top: 10px;
            margin-left: 0;
            width: 100%;
            text-align: center;
        }
    }

    /* --- CSS para las IMÁGENES y Alineación de SECCIONES --- */

    /* Estilos base para todas las imágenes dentro de .card-body */
    .card-body img {
        max-width: 100%;
        height: auto;
        display: block; /* Asegura que cada imagen ocupe su propia línea por defecto */
        margin-bottom: 1.5rem; /* Espacio entre imágenes apiladas */
    }

    /* Alineación de los CONTENEDORES .row de cada sección */
    /* Por defecto, los grupos de imágenes estarán a la izquierda */
    .card-body > h3 + hr + .row {
        justify-content: flex-start !important; /* Alinea los grupos a la izquierda */
    }

    /* Alineación del GRUPO de imágenes "Durante una Inundación" a la derecha */
    /* Usamos nth-of-type(1) para el segundo h3 (que es 'Durante una Inundación' si 'EN CASA' está antes) */
    .card-body > h3.mt-4:nth-of-type(1) + hr + .row { 
        justify-content: flex-end !important;
        
    }

    /* CONVERTIR EL CONTENEDOR DE IMÁGENES 'ANTES' A FLEXBOX para control granular de antes2.png */
    @media (min-width: 768px) { /* SOLO en pantallas medianas y grandes */
        .card-body > h3:first-of-type + hr + .row > .col-md-8 {
            display: flex; /* Hacemos que el col-md-8 sea un contenedor flex */
            flex-direction: row; /* Los items intentan ponerse en fila */
            flex-wrap: wrap; /* Permite que los items bajen a la siguiente línea si no hay espacio */
            align-items: flex-start; /* Alinea los items al inicio del eje transversal */
            /* No necesitamos justify-content aquí si manejamos con auto margins y widths */
        }

        /* Estilo para las imágenes dentro del contenedor flex de la sección 'Antes' */
        .card-body > h3:first-of-type + hr + .row > .col-md-8 > img {
            flex-shrink: 0; /* No encoger las imágenes */
            flex-grow: 0;  /* No estirar las imágenes */
            /* Si quieres que antes1 y antes3 estén una al lado de la otra con antes2 en la misma fila a la derecha: */
            /* width: 45%; /* Ejemplo: Si quieres 2 imágenes por fila, ajusta el ancho */
            /* margin-bottom: 1rem; /* Ajusta el margen si las pones en fila */
        }
        
        /* Alinear la imagen antes2.png con margen exacto en pantallas grandes */
        .card-body > h3:first-of-type + hr + .row > .col-md-8 > img[src="assets/img/antes2.png"] {
            margin-left: auto; /* Empuja antes2 a la derecha del todo dentro de su línea/fila */
            margin-right: 150px !important; /* <--- ¡AQUÍ AJUSTA EL VALOR EN PX para moverla desde la DERECHA! */
                                 /* Puedes probar 0px si quieres que quede pegada al borde derecho del col-md-8 */
                                 /* O un valor positivo para separarla del borde derecho */
                                 /* O un valor negativo para que sobresalga (si el contenedor no tiene overflow: hidden) */
            order: 1; /* Esto ayuda a que, en el contexto flex, se posicione después de otros elementos,
                         pero visualmente margin-left: auto ya hace el trabajo de moverla a la derecha. */
        }
    }


    /* Media queries para dispositivos pequeños (sm y xs) */
    @media (max-width: 767.98px) { 
        .card-body {
            padding: 1.5rem;
        }
        h3 {
            font-size: 1.5rem;
        }
        /* En móvil, los grupos de imágenes se centran. Esto es lo que causó el centrado general. */
        .card-body > h3 + hr + .row {
            justify-content: center !important; /* Mantiene los grupos centrados en móvil */
        }
        /* Para la sección 'Antes', el contenedor de imágenes vuelve a display: block en móvil */
        .card-body > h3:first-of-type + hr + .row > .col-md-8 {
            display: block; /* Deshabilita flexbox para que las imágenes se apilen */
        }
        /* La imagen antes2.png se centra también en móvil */
        .card-body > h3:first-of-type + hr + .row > .col-md-8 > img[src="assets/img/antes2.png"] {
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
    }

    /* Estilos para el Pie de Página (Footer) */
    footer {
        background-color: #f8f9fa;
        color: #6c757d;
        font-size: 0.9rem;
        border-top: 1px solid #e9ecef;
        padding: 1.5rem 0;
        text-align: center;
    }
</style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">AquaLerta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="educacion.php">Educación</a></li>
                    <li class="nav-item"><a class="nav-link" href="alertas.php">Alertas</a></li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="login.php">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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

    <footer class="text-center mt-5 py-3 bg-light">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> AquaLerta · Educación ante Emergencias</p>
        </div>
    </footer>

    <script src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js)" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
</script>
</body>
</html>