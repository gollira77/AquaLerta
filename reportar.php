<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="container my-5">
    <h1>Reportar una Alerta</h1>
    <p>Utiliza este formulario para informar sobre una situación de emergencia o riesgo en tu barrio.</p>

    <form id="report-form" action="procesar_reporte.php" method="POST">
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción de la situación:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required placeholder="Describe lo que está sucediendo"></textarea>
        </div>

        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación :</label>
            <input type="text" class="form-control" id="ubicacion" name="ubicacion_texto" readonly placeholder="Detectando ubicación...">
            <input type="hidden" id="latitud" name="latitud">
            <input type="hidden" id="longitud" name="longitud">
            <small class="form-text text-muted">Asegúrate de permitir el acceso a tu ubicación en el navegador.</small>
        </div>

        <button type="submit" class="btn btn-danger">Enviar Alerta</button>

        <div id="confirmation-message" class="alert alert-success mt-3 d-none" role="alert">
            ¡Alerta enviada con éxito! Gracias por tu reporte.
        </div>
        <div id="error-message" class="alert alert-danger mt-3 d-none" role="alert">
            Hubo un error al enviar tu reporte. Por favor, inténtalo de nuevo.
        </div>
    </form>
</main>

<?php include 'includes/footer.php'; ?>

<script src="assets/js/script.js"></script>
<script>
    // Lógica para obtener la ubicación del usuario
    document.addEventListener('DOMContentLoaded', function() {
        const ubicacionInput = document.getElementById('ubicacion');
        const latitudInput = document.getElementById('latitud');
        const longitudInput = document.getElementById('longitud');
        const confirmationMessage = document.getElementById('confirmation-message');
        const errorMessage = document.getElementById('error-message');
        const reportForm = document.getElementById('report-form');

        if (navigator.geolocation) {
            ubicacionInput.value = 'Obteniendo ubicación...';
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    latitudInput.value = lat;
                    longitudInput.value = lon;
                    ubicacionInput.value = `Latitud: ${lat}, Longitud: ${lon}`;
                },
                function(error) {
                    console.error('Error al obtener la ubicación:', error);
                    ubicacionInput.value = 'Ubicación no disponible.';
                    alert('No pudimos detectar tu ubicación. Por favor, asegúrate de permitir el acceso a la geolocalización en tu navegador.');
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
            );
        } else {
            ubicacionInput.value = 'Geolocalización no soportada por tu navegador.';
            alert('Tu navegador no soporta la geolocalización. La ubicación no se enviará.');
        }

        reportForm.addEventListener('submit', function(event) {
            event.preventDefault(); 

            confirmationMessage.classList.add('d-none'); // Ocultar mensajes anteriores
            errorMessage.classList.add('d-none');

            const formData = new FormData(reportForm);

            fetch(reportForm.action, {
                method: reportForm.method,
                body: formData
            })
            .then(response => response.text()) // Espera una respuesta de texto 
            .then(data => {
                if (data.trim() === 'success') {
                    confirmationMessage.classList.remove('d-none');
                    reportForm.reset(); // Limpiar el formulario
                    // Ocultar mensaje de confirmación después de 5 segundos
                    setTimeout(() => {
                        confirmationMessage.classList.add('d-none');
                    }, 5000);
                } else {
                    errorMessage.classList.remove('d-none');
                    console.error('Error del servidor:', data);
                }
            })
            .catch(error => {
                errorMessage.classList.remove('d-none');
                console.error('Error de red o JavaScript:', error);
            });
        });
    });
</script>