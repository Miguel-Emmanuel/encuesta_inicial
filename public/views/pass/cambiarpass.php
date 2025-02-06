<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reestablecer Contraseña</title>
    
    <!-- Bootstrap CSS -->
    <link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <?php include("../../../app/Models/conexion.php"); ?>
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

<?php 
$id = $_GET["token"];
$verificacion = $conexion->query("DELETE FROM links WHERE created_at < (NOW() - INTERVAL 15 MINUTE);");
$sql = $conexion->query("SELECT * FROM links WHERE id_usuario = '$id'");

if ($datos = $sql->fetch_object()) { 
?>
    <div class="col-md-6 p-4 shadow rounded-3 bg-white" style="border: solid 1px black;">
        <h2 class="text-center fw-bold">Restablecer Contraseña</h2>
        <hr>

        <form method="post" action="">
            <?php include("../../../app/Controllers/cambiarpass_controller.php"); ?>

            <!-- Nueva Contraseña -->
            <div class="mb-3">
                <label for="pass1" class="form-label fw-medium">Nueva Contraseña</label>
                <div class="input-group">
                    <input type="password" name="pass1" id="pass1" class="form-control" 
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
                    <input type="password" name="pass2" id="pass2" class="form-control"
                        placeholder="Repita Nueva Contraseña" required onkeyup="validatePassword()">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('pass2', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div id="pass2-tooltip" class="form-text text-danger d-none">
                    Las contraseñas no coinciden.
                </div>
            </div>

            <!-- Botón Submit -->
            <div class="text-center">
                <button type="submit" name="btncambiarpass" id="submit-btn" class="btn btn-success w-100" disabled>
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>

<?php } else { ?>
    <div class="col-md-6 p-4 shadow rounded-3 bg-white text-center">
        <h4 class="text-danger">❌ No se encontró una solicitud válida en nuestros servidores.</h4>
    </div>
<?php } ?>

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

    pass1Tooltip.classList.toggle("d-none", validPass1 || pass1.value === "");
    pass2Tooltip.classList.toggle("d-none", passwordsMatch || pass2.value === "");

    submitBtn.disabled = !(validPass1 && passwordsMatch);
}
</script>

<!-- Bootstrap JS -->
<script src="../../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
