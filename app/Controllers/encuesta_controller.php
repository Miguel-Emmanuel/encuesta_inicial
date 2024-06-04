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
            foreach ($respuesta as $opcionId => $opcionRespuesta) {
                if (is_array($opcionRespuesta)) {
                    foreach ($opcionRespuesta as $opcion2 => $valor) {
                        $opcionId2 = obtenerOpcionId($conexion, $idPregunta, $opcionId, $opcion2);
                        if ($opcionId2 !== null) {
                            $respuestaTexto = "$opcionId - $opcion2";
                            guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId2, $respuestaTexto);
                        }
                    }
                } else {
                    $opcionId1 = obtenerOpcionId($conexion, $idPregunta, $opcionId);
                    if ($opcionId1 !== null) {
                        $respuestaTexto = $opcionRespuesta;
                        guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId1, $respuestaTexto);
                    }
                }
            }
        } else {
            guardarRespuesta($conexion, $idUsuario, $idPregunta, null, $respuesta);
        }
    }
}

function obtenerOpcionId($conexion, $idPregunta, $opcion1, $opcion2 = null) {
    if ($opcion2 === null) {
        $stmt = $conexion->prepare("SELECT id FROM opciones_respuesta WHERE pregunta_id = ? AND opcion1 = ?");
        $stmt->bind_param("is", $idPregunta, $opcion1);
    } else {
        $stmt = $conexion->prepare("SELECT id FROM opciones_respuesta WHERE pregunta_id = ? AND opcion1 = ? AND opcion2 = ?");
        $stmt->bind_param("iss", $idPregunta, $opcion1, $opcion2);
    }
    
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    
    return $id ? $id : null;
}

function guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $respuestaTexto) {
    if ($opcionId === null) {
        $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO usuario_respuesta (usuario_id, pregunta_id, respuesta_texto, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmtUsuarioRespuesta->bind_param("iis", $idUsuario, $idPregunta, $respuestaTexto);
    } else {
        $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO usuario_respuesta (usuario_id, pregunta_id, opcion_id, respuesta_texto, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmtUsuarioRespuesta->bind_param("iiis", $idUsuario, $idPregunta, $opcionId, $respuestaTexto);
    }
    
    $stmtUsuarioRespuesta->execute();

    if ($stmtUsuarioRespuesta->affected_rows <= 0) {
        echo "Error al registrar la respuesta del usuario.";
    }
    $stmtUsuarioRespuesta->close();
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seccionActual = $_POST['seccionActual'];
    // $nuevaSeccion = $_POST['nuevaSeccion'];

    // Redirigir a la página de visualización de la sección actual
    header("Location: ../../public/views/encuesta/encuesta.php?seccion=$seccionActual");

    exit;
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
