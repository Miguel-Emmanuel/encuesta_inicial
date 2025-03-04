<!-- Modal -->
    <!-- Agregar Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-semibold text-dark" id="exampleModalLabel" style="font-family: 'Poppins', sans-serif;">
                    Cambiar Contraseña
                </h5>
            </div>
            <div class="modal-body px-4" style="font-family: 'Poppins', sans-serif;">
                <form method="post" action="../../../app/Controllers/verificaremail.php">
                    <input type="hidden" name="id" value="<?php echo $idUsuario; ?>">

                    <!-- Nueva Contraseña -->
                    <div class="mb-3">
                    <p style = "color:black">Hola!, parece que este es tu primer inicio de sesión, por motivos de seguridad, 
                        por favor actualiza tu contraseña e inicia sesión nuevamente. </p> 
                        <label for="pass1" class="form-label fw-medium">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="pass1" id="pass1" class="form-control rounded-2"
                                placeholder="Nueva Contraseña" required
                                pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                title="Debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial."
                                onkeyup="validatePassword()">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('pass1', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="pass1-tooltip" class="form-text text-danger d-none">
                            La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label for="pass2" class="form-label fw-medium">Confirmar Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="pass2" id="pass2" class="form-control rounded-2"
                                placeholder="Repita Nueva Contraseña" required
                                onkeyup="validatePassword()">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('pass2', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="pass2-tooltip" class="form-text text-danger d-none">
                            Las contraseñas no coinciden.
                        </div>
                    </div>

                    <div class="form-text text-muted">Asegúrate de cumplir los requisitos antes de continuar.</div>
            </div>
            <div class="modal-footer border-0">
                <a href="/app/Controllers/sessiondestroy_controller.php"><input type="button" class="btn btn-secondary rounded-2" value="Cerrar Sesión"></a>
                <button type="submit" id="submit-btn" class="btn btn-success rounded-2" disabled>Cambiar Contraseña</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para mostrar/ocultar contraseña y validaciones -->
<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }
}

function validatePassword() {
    const pass1 = document.getElementById("pass1");
    const pass2 = document.getElementById("pass2");
    const pass1Tooltip = document.getElementById("pass1-tooltip");
    const pass2Tooltip = document.getElementById("pass2-tooltip");
    const submitBtn = document.getElementById("submit-btn");

    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    
    let validPass1 = passwordPattern.test(pass1.value);
    let passwordsMatch = pass1.value === pass2.value && pass2.value !== "";

    // Mostrar/ocultar tooltip de la contraseña
    if (!validPass1 && pass1.value !== "") {
        pass1Tooltip.classList.remove("d-none");
    } else {
        pass1Tooltip.classList.add("d-none");
    }

    // Mostrar/ocultar tooltip de coincidencia
    if (!passwordsMatch && pass2.value !== "") {
        pass2Tooltip.classList.remove("d-none");
    } else {
        pass2Tooltip.classList.add("d-none");
    }

    // Habilitar botón si todo está correcto
    submitBtn.disabled = !(validPass1 && passwordsMatch);
}
</script>
