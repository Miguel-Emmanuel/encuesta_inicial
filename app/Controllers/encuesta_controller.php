<?php
include("../../database/conexion.php");

session_start();
if (empty($_SESSION["id"])) {
    header("location: ../../view/sesiones/login.php");
    exit;
}

$idUsuario = $_SESSION["id"];

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuestas = $_POST['respuestas'];

    foreach ($respuestas as $idPregunta => $respuesta) {
        if (is_array($respuesta)) {
            foreach ($respuesta as $opcion1 => $opcion2Array) {
                if (is_array($opcion2Array)) {
                    foreach ($opcion2Array as $opcion2 => $valor) {
                        $respuestaTexto = "$opcion1 - $opcion2";
                        guardarRespuesta($conexion, $idUsuario, $idPregunta, null, $respuestaTexto);
                    }
                } else {
                    $respuestaTexto = $opcion1;
                    guardarRespuesta($conexion, $idUsuario, $idPregunta, null, $respuestaTexto);
                }
            }
        } else {
            guardarRespuesta($conexion, $idUsuario, $idPregunta, null, $respuesta);
        }
    }
}

function guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $respuestaTexto) {
    $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO usuario_respuesta (usuario_id, pregunta_id, opcion_id, respuesta_texto, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");

    // Aquí estamos usando cinco parámetros: dos enteros, un entero o NULL, y dos strings
    $stmtUsuarioRespuesta->bind_param("iiiss", $idUsuario, $idPregunta, $opcionId, $respuestaTexto, $respuestaTexto);
    $stmtUsuarioRespuesta->execute();

    if ($stmtUsuarioRespuesta->affected_rows <= 0) {
        echo "Error al registrar la respuesta del usuario.";
    }
    $stmtUsuarioRespuesta->close();
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Contestación</title>
    <link rel="stylesheet" href="../../../public/css/encuesta_c.css">
</head>
<body>
    <div class="container">
        <p>Gracias por contestar el formulario</p>
        <a href="../../../app/Controllers/sessiondestroy_controller.php" class="btn">
            <input type="submit" name="btningresar" class="btn btn-success" value="Cerrar sesión">
        </a>
    </div>
</body>
</html>
