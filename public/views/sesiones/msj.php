<?php
// Mensaje de sesión cerrada correctamente
if (isset($_REQUEST['sc'])) {
    ?>
    <div class="alert alert-success">La sesión ha finalizado con éxito!</div>
    <?php
}

// Error, no existe el correo o la contraseña es incorrecta
if (isset($_REQUEST['e'])) {
    $error_code = $_REQUEST['e'];
    switch ($error_code) {
        case '1': // Error en las credenciales de login
            ?>
            <div class="alert alert-danger">Error en las credenciales, revise nuevamente!</div>
            <?php
            break;

        case '2': // Error en la conexión a la base de datos
            ?>
            <div class="alert alert-danger">Hubo un problema con la conexión a la base de datos. Por favor, inténtelo nuevamente más tarde.</div>
            <?php
            break;

        case '3': // Error desconocido o general
            ?>
            <div class="alert alert-danger">Hubo un error desconocido. Por favor, intente nuevamente.</div>
            <?php
            break;

        default: // Caso no especificado
            ?>
            <div class="alert alert-danger">Ocurrió un error, por favor intente nuevamente.</div>
            <?php
            break;
    }
}
?>
