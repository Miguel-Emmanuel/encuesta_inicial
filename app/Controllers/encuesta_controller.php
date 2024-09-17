<?php
        include("../../database/conexion.php");

        session_start();
        if (empty($_SESSION["id"])) {
            header("location: ../../view/sesiones/login.php");
            exit;
        }

        $idUsuario = $_SESSION["id"];


        // // Inspeccionar el contenido de $_POST
        // echo "<pre>";
        // var_dump($_POST);  // Muestra toda la información enviada en el formulario
        // echo "</pre>";
        // // exit;




        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $respuestas = $_POST['respuestas'];
            //var_dump($respuestas);

            foreach ($respuestas as $idPregunta => $respuesta) {
                $seccionId = obtenerSeccionId($conexion, $idPregunta);
            
                if (is_array($respuesta)) {
                    // Caso de múltiples opciones, incluso si solo hay una opción seleccionada
                    foreach ($respuesta as $opcionId => $opcionRespuesta) {
                        // En este caso, las claves son los textos de las opciones (por ejemplo, "Lunes")
                        // Si los valores están vacíos, todavía necesitamos procesarlos
            
                        $opcionId1 = obtenerOpcionId($conexion, $idPregunta, $opcionId);
            
                        if ($opcionId1 !== null) {
                            // Guardar tanto en `opcion_id` como en `respuesta_texto` la clave (por ejemplo, "Lunes")
                            guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId1, $seccionId, $opcionId);
                        }
                    }
                } else {
                    // Caso de una sola opción (radio button o select simple)
                    $opcionId = obtenerOpcionId($conexion, $idPregunta, $respuesta);
                    if ($opcionId !== null) {
                        // Guardar tanto en `opcion_id` como en `respuesta_texto`
                        guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $seccionId, $respuesta);
                    } else {
                        // Fallback en caso de no encontrar un `opcion_id`, guarda el texto en `respuesta_texto`
                        guardarRespuesta($conexion, $idUsuario, $idPregunta, null, $seccionId, $respuesta);
                    }
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

        function obtenerSeccionId($conexion, $idPregunta) {
            $stmt = $conexion->prepare("SELECT seccion_id FROM preguntas WHERE id = ?");
            $stmt->bind_param("i", $idPregunta);
            $stmt->execute();
            $stmt->bind_result($seccionId);
            $stmt->fetch();
            $stmt->close();
            
            return $seccionId;
        }

        function guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto) {
            if ($opcionId === null) {
                $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO usuario_respuesta (usuario_id, pregunta_id, seccion_id, respuesta_texto, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmtUsuarioRespuesta->bind_param("iiis", $idUsuario, $idPregunta, $seccionId, $respuestaTexto);
            } else {
                $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO usuario_respuesta (usuario_id, pregunta_id, opcion_id, seccion_id, respuesta_texto, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
                $stmtUsuarioRespuesta->bind_param("iiiis", $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto);
            }
            
            $stmtUsuarioRespuesta->execute();

            if ($stmtUsuarioRespuesta->affected_rows <= 0) {
                echo "Error al registrar la respuesta del usuario.";
            }
            $stmtUsuarioRespuesta->close();
        }


        if ($seccionId !== null) {
            $nombreSeccion = null;
            $consultaSeccion = "SELECT nombre FROM secciones WHERE id = ?";
            $stmtSeccion = $conexion->prepare($consultaSeccion);
            $stmtSeccion->bind_param("i", $seccionId);
            $stmtSeccion->execute();
            $stmtSeccion->bind_result($nombreSeccion);
            $stmtSeccion->fetch();
            $stmtSeccion->close();
        }
        // Puedes personalizar el mensaje de la notificación aquí
        $mensaje = "¡Redirección exitosa!";

        // Codificar el mensaje para que pueda pasar por la URL
        $mensajeCodificado = urlencode($mensaje.$nombreSeccion);

        // Redirigir a la página de destino junto con el mensaje de notificación
        header("Location: ../../public/views/encuesta/menu_secciones.php");
        exit; // Asegúrate de salir del script después de la redirección


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