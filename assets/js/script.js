    document.addEventListener("DOMContentLoaded", function () {
    console.log("Página cargada correctamente");

    // Mostrar u ocultar contraseñas (si existen)
    document.querySelectorAll(".toggle-password").forEach(function (btn) {
        btn.addEventListener("click", function () {
        const input = document.getElementById(this.dataset.toggle);
        if (input.type === "password") {
            input.type = "text";
            this.textContent = "Ocultar";
        } else {
            input.type = "password";
            this.textContent = "Mostrar";
        }
        });
    });

    // Validaciones en formularios (si existe el formulario de registro)
    const form = document.getElementById("formRegistro");
    if (form) {
        form.addEventListener("submit", function (e) {
        const nombre = document.getElementById("nombre").value;
        const apellido = document.getElementById("apellido").value;
        const telefono = document.getElementById("telefono").value;
        const pass1 = document.getElementById("password").value;
        const pass2 = document.getElementById("confirmar_password").value;

        const soloLetras = /^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+$/;
        const soloNumeros = /^[0-9]+$/;

        if (!soloLetras.test(nombre)) {
            alert("El nombre no puede contener números ni caracteres especiales.");
            e.preventDefault();
            return;
        }
        if (!soloLetras.test(apellido)) {
            alert("El apellido no puede contener números ni caracteres especiales.");
            e.preventDefault();
            return;
        }
        if (!soloNumeros.test(telefono)) {
            alert("El teléfono solo puede contener números.");
            e.preventDefault();
            return;
        }
        if (pass1 !== pass2) {
            alert("Las contraseñas no coinciden.");
            e.preventDefault();
            return;
        }
        });
    }
    });
