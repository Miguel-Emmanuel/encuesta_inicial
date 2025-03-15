<?php
if (isset($_REQUEST['e'])) {
    $mensajes = [
        '1' => "Error en las credenciales, revise nuevamente!",
        '2' => "No hay conexión con el sistema. Intente más tarde.",
        '3' => "Hubo un error desconocido. Por favor, intente nuevamente.",
    ];

    $mensaje = $mensajes[$_REQUEST['e']] ?? "Ocurrió un error, por favor intente nuevamente.";
    echo "<div class='alert alert-danger'>{$mensaje}</div>";
}

// Mensaje de sesión cerrada correctamente
if (isset($_REQUEST['sc'])) {
    echo "<div class='alert alert-success'>La sesión ha finalizado con éxito!</div>";
}
?>
