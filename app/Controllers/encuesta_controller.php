<?php
include("../../database/conexion.php");

session_start();
if (empty($_SESSION["id"])){
    header("location: ../../view/sesiones/login.php");
    exit; // Termina la ejecución del script después de redirigir
}
$idUsuario = $_SESSION["id"];

///////////////////DAR DE ALTA VARIAS RESPUESTAS////////////////////


// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener las respuestas del formulario
    $respuestas = $_POST['respuestas'];
    // Recorrer todas las respuestas y dar de alta en la base de datos
    foreach ($respuestas as $idPregunta => $respuestaTexto) {
        // Insertar la respuesta en la tabla respuestas
        $stmtRespuesta = $conexion->prepare("INSERT INTO respuestas (respuesta, pregunta_id) VALUES (?, ?)");
        $stmtRespuesta->bind_param("si", $respuestaTexto, $idPregunta);
        $stmtRespuesta->execute();
        // Verificar si la inserción en respuestas fue exitosa
        if ($stmtRespuesta->affected_rows > 0) {
            // Obtener el ID de la respuesta recién creada
            // echo "La respuesta se registró correctamente.";
            $idRespuesta = $stmtRespuesta->insert_id;
            // Insertar la relación entre el usuario y la respuesta en la tabla usuario_respuesta
            $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO usuario_respuesta (usuario_id, respuesta_id) VALUES (?, ?)");
            $stmtUsuarioRespuesta->bind_param("ii", $idUsuario, $idRespuesta);
            $stmtUsuarioRespuesta->execute();
            // Verificar si la inserción en usuario_respuesta fue exitosa
            if ($stmtUsuarioRespuesta->affected_rows <= 0) {
                echo "Error al registrar la respuesta del usuario.";
            }
        } else {
            echo "Error al registrar la respuesta.";
        }
        // Cerrar la sentencia de inserción de respuesta
        $stmtRespuesta->close();
    }
    // if ($stmtRespuesta->affected_rows > 0) {
    //     // Obtener el ID de la respuesta recién creada
    //     echo "La respuesta se registró correctamente.";
    // }
}

// Cerrar la conexión
$conexion->close();









/////*****PARA DAR DE ALTA UNA SOLA PREGUNTA Y RESPUESA********************** */
//////////////////////////////////////////////////////////////////////////////////////////////////
// // Obtener el ID de usuario de la sesión
// $idUsuario = $_SESSION["id"];

// // Obtener el ID de la pregunta específica
// $sql = $conexion->query("SELECT * FROM preguntas WHERE id = 1");
// if ($pregunta = $sql->fetch_object()) {
//     $idPregunta = $pregunta->id;
//     $preguntaa = $pregunta->pregunta;
// }

// // Verificar si se ha enviado el formulario
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Obtener la respuesta del formulario
//     $respuestaTexto = $_POST['respuesta']; 
//     // Insertar la respuesta en la tabla respuestas
//     $stmtRespuesta = $conexion->prepare("INSERT INTO respuestas (respuesta, pregunta_id) VALUES (?, ?)");
//     $stmtRespuesta->bind_param("si", $respuestaTexto, $idPregunta);
//     $stmtRespuesta->execute();

//     // Verificar si la inserción en respuestas fue exitosa
//     if ($stmtRespuesta->affected_rows > 0) {
//         // Obtener el ID de la respuesta recién creada
//         $idRespuesta = $stmtRespuesta->insert_id;
//         // Insertar la relación entre el usuario y la respuesta en la tabla usuario_respuesta
//         $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO usuario_respuesta (usuario_id, respuesta_id) VALUES (?, ?)");
//         $stmtUsuarioRespuesta->bind_param("ii", $idUsuario, $idRespuesta);
//         $stmtUsuarioRespuesta->execute();

//         // Verificar si la inserción en usuario_respuesta fue exitosa
//         if ($stmtUsuarioRespuesta->affected_rows > 0) {
//             echo "La respuesta se registró correctamente.";
//         } else {
//             echo "Error al registrar la respuesta del usuario.";
//         }
//     } else {
//         echo "Error al registrar la respuesta.";
//     }
// }

// // Cerrar las conexiones
// $stmtRespuesta->close();
// $stmtUsuarioRespuesta->close();
// $conexion->close();
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
