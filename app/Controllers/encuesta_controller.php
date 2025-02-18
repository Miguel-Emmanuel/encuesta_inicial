<?php
        include("../../database/conexion.php");

        session_start();
        if (empty($_SESSION["id"])) {
            header("location: ../../view/sesiones/login.php");
            exit;
        }

        // $idUser = $_SESSION["id"];

// Obtener estudiante_id usando usuario_id de la sesión
$stmt = $conexion->prepare("SELECT id FROM estudiantes WHERE usuario_id = ?");
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$stmt->bind_result($idUsuario);
$stmt->fetch();
$stmt->close();

        // Inspeccionar el contenido de $_POSTp
        // echo "<pre>";
        // var_dump($_POST);  // Muestra toda la información enviada en el formulario
        // echo "</pre>";
        // exit;




        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $respuestas = $_POST['respuestas'];
            $respuestas_otro = $_POST['respuestas_otro'] ?? []; // Capturamos las respuestas dinámicas
//         var_dump("Respuestas generales" .$respuestas . "respuestas dimanicas" . $respuestas_otro);
//         ?>
// <script>
//     console.log("Respuestas generales" + respuestas + "respuestas dimanicas" + respuestas_otro)
// </script>
        <?php 
            foreach ($respuestas as $idPregunta => $respuesta) {
                $seccionId = obtenerSeccionId($conexion, $idPregunta);
        

    // Si la pregunta es país (17), estado (18) o municipio (19)
    // if (in_array($idPregunta, [17, 18, 19])) {
    //     // Separar el valor recibido (id y nombre)
    //     list($opcionId, $respuestaTexto) = explode(',', $respuesta);
        
    //     // Guardar el ID en opcion_id y el nombre en respuesta_texto
    //     guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto);
    // } 



                // Obtener si existe un campo dinámico para la misma pregunta
                $respuestaTexto = $respuestas_otro[$idPregunta] ?? null; 
        
                if (is_array($respuesta)) {
                    foreach ($respuesta as $opcionId => $opcionRespuesta) {
                        $opcionId1 = obtenerOpcionId($conexion, $idPregunta, $opcionId);
                        if ($opcionId1 !== null) {
                            guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId1, $seccionId, $respuestaTexto ?: $opcionId);
                        }
                    }
                } else {
                    // Caso de una sola opción (radio button o select simple)
                    $opcionId = obtenerOpcionId($conexion, $idPregunta, $respuesta);
                    if ($opcionId !== null) {
                        // Guardar tanto en opcion_id como en respuesta_texto
                        guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto ?: $respuesta);
                    } else {
                        // Fallback en caso de no encontrar un opcion_id, guarda el texto en respuesta_texto
                        guardarRespuesta($conexion, $idUsuario, $idPregunta, null, $seccionId, $respuestaTexto ?: $respuesta);
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


        // function obtenerUbicacionId($conexion, $tabla, $nombre, $columnaRelacion = null, $idRelacion = null) {
        //     $consulta = "SELECT id FROM $tabla WHERE nombre = ?";
        //     if ($columnaRelacion !== null && $idRelacion !== null) {
        //         $consulta .= " AND $columnaRelacion = ?";
        //     }
        
        //     $stmt = $conexion->prepare($consulta);
            
        //     if ($columnaRelacion !== null && $idRelacion !== null) {
        //         $stmt->bind_param("si", $nombre, $idRelacion); // Se espera que el nombre sea string y la relación int
        //     } else {
        //         $stmt->bind_param("s", $nombre);
        //     }
        
        //     $stmt->execute();
        //     $stmt->bind_result($id);
        //     $stmt->fetch();
        //     $stmt->close();
        
        //     return $id ? $id : null;
        // }
        

        function guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto) {

            /////insercion a tabla respuestas/////////////
            $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO respuestas (pregunta_id, estudiante_id, respuesta, created_at) VALUES (?, ?, ?, NOW())");

            if (!$stmtUsuarioRespuesta) {
                echo "Error en prepare(): " . $conexion->error;
            }
            
            $stmtUsuarioRespuesta->bind_param("iis", $idPregunta, $idUsuario, $respuestaTexto);
            
            if (!$stmtUsuarioRespuesta->execute()) {
                echo "Error en execute(): " . $stmtUsuarioRespuesta->error;
            }
            /////////////////////////////////////////////////////////

            //////////////////insercion a tbabla estudiantes_respiestas///////////////
            // var_dump( "Respuestas generales" .$respuestas . "respuestas dimanicas" . $respuestas_otro   );
            // var_dump(in_array($idPregunta, [17, 18, 19]));
            if ($opcionId === null) {
                $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO estudiante_respuesta (estudiante_id, pregunta_id, seccion_id, respuesta_texto, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmtUsuarioRespuesta->bind_param("iiis", $idUsuario, $idPregunta, $seccionId, $respuestaTexto);

            } else {
                $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO estudiante_respuesta (estudiante_id, pregunta_id, opcion_id, seccion_id, respuesta_texto, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
                $stmtUsuarioRespuesta->bind_param("iiiis", $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto);
            }
            
            $stmtUsuarioRespuesta->execute();
            if ($stmtUsuarioRespuesta->affected_rows <= 0) {
                echo "Error al registrar la respuesta del usuario.";
            }
            
            // Verificar si la pregunta es sobre género (por ejemplo, si la pregunta tiene ID 9)
            // if ($idPregunta == 9) {
            //     // Actualizar la tabla usuarios con el género seleccionado
            //     $stmtActualizarGenero = $conexion->prepare("UPDATE estudiantes SET i_genero = (SELECT id FROM i_genero WHERE nombreig = ?) WHERE id = ?");
            //     $stmtActualizarGenero->bind_param("si", $respuestaTexto, $idUsuario);
                
            //     $stmtActualizarGenero->execute();
                
            //     if ($stmtActualizarGenero->affected_rows <= 0) {
            //         echo "Error al actualizar el género en la tabla de usuarios.";
            //     }
            //     $stmtActualizarGenero->close();
            // }
            // if (in_array($idPregunta, [17, 18, 19])) {
            //     // Preguntas de país (17), estado (18) y municipio (19)
            //     if ($idPregunta == 17) {
            //         // Obtener el ID del país
            //         $paisId = obtenerUbicacionId($conexion, 'paises', $respuestaTexto);
            //         // var_dump($paisId);
            //         guardarRespuesta($conexion, $idUsuario, $idPregunta, $paisId, $seccionId, $respuestaTexto);
            //     } elseif ($idPregunta == 18) {
            //         // Obtener el ID del estado relacionado con el país seleccionado
            //         $estadoId = obtenerUbicacionId($conexion, 'estados', $respuestaTexto, 'pais', $paisId);
            //         guardarRespuesta($conexion, $idUsuario, $idPregunta, $estadoId, $seccionId, $respuestaTexto);
            //     } elseif ($idPregunta == 19) {
            //         // Obtener el ID del municipio relacionado con el estado seleccionado
            //         $municipioId = obtenerUbicacionId($conexion, 'municipios', $respuestaTexto, 'estado', $estadoId);
            //         guardarRespuesta($conexion, $idUsuario, $idPregunta, $municipioId, $seccionId, $respuestaTexto);
            //     }
            // } else {
            //     // Guardado normal para las demás preguntas
            //     guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto ?: $respuesta);
            // }
            



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