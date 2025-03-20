<?php
include("../../database/conexion.php");

session_start();
if (empty($_SESSION["id"])) {
    header("location: ../../view/sesiones/login.php");
    exit;
}

// Obtener estudiante_id usando usuario_id de la sesión
$stmt = $conexion->prepare("SELECT id FROM estudiantes WHERE usuario_id = ?");
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$stmt->bind_result($idUsuario);
$stmt->fetch();
$stmt->close();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";
    // exit;
    // Obtener respuestas del formulario
    $respuestas = $_POST['respuestas'];
    $respuestas_otro = $_POST['respuestas_otro'] ?? []; // Capturamos las respuestas dinámicas

    // Guardar respuestas en las tablas correspondientes
    foreach ($respuestas as $idPregunta => $respuesta) {
        $seccionId = obtenerSeccionId($conexion, $idPregunta);
        
        // Verificar si existe un campo dinámico para la misma pregunta
        $respuestaTexto = $respuestas_otro[$idPregunta] ?? null; 
        
        if (is_array($respuesta)) {
            foreach ($respuesta as $opcionId => $opcionRespuesta) {
                $opcionId1 = obtenerOpcionId($conexion, $idPregunta, $opcionId);
                if ($opcionId1 !== null) {
                    guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId1, $seccionId, $respuestaTexto ?: $opcionId);
                }
            }
        } else {
            $opcionId = obtenerOpcionId($conexion, $idPregunta, $respuesta);
            if ($opcionId !== null) {
                // Guardar en opción y en texto (si es que se ha enviado respuesta dinámica)
                guardarRespuesta($conexion, $idUsuario, $idPregunta, $opcionId, $seccionId, $respuestaTexto ?: $respuesta);
            } else {
                // Si no se encontró opción, se guarda el texto de la respuesta
                guardarRespuesta($conexion, $idUsuario, $idPregunta, null, $seccionId, $respuestaTexto ?: $respuesta);
            }
        }
    }
// ------------------------------------------------------------
// INTEGRACIÓN: Evaluación de clasificación de respuestas
// ------------------------------------------------------------

// Bandera para activar o desactivar la depuración (para pruebas)
$debug = true;
function evaluarCondicion($respuesta, $operador, $valor, $debug = true) {
    $respuesta = trim($respuesta);
    $valor = trim($valor);

    if ($debug) {
        echo "<pre>";
        echo ">>> Entré a evaluarCondicion <<<\n";
        var_dump("Comparando respuesta: [" . $respuesta . "] con valor: [" . $valor . "] usando operador: " . $operador);
        echo "</pre>";
    }
    
    switch ($operador) {
        case '=':
            return $respuesta == $valor;
        case '!=':
            return $respuesta != $valor;
        case 'IN':
            $valores = array_map('trim', explode(',', $valor));
            if ($debug) {
                echo "<pre>Array IN: ";
                print_r($valores);
                echo "</pre>";
            }
            return in_array($respuesta, $valores);
        case 'NOT IN':
            $valores = array_map('trim', explode(',', $valor));
            if ($debug) {
                echo "<pre>Array NOT IN: ";
                print_r($valores);
                echo "</pre>";
            }
            return !in_array($respuesta, $valores);
        case '<=':
            return floatval($respuesta) <= floatval($valor);
        case '>':
            return floatval($respuesta) > floatval($valor);
        default:
            return false;
    }
}

 
// Recorrer cada respuesta para evaluar las reglas de clasificación
foreach ($respuestas as $idPregunta => $respuesta) {
    // Si la respuesta es un array (varias opciones), evaluamos cada una
    if (is_array($respuesta)) {
        foreach ($respuesta as $item) {
            // Se usa la respuesta dinámica si existe, de lo contrario se toma el valor actual
            $respuesta_text = !empty($respuestas_otro[$idPregunta]) ? $respuestas_otro[$idPregunta] : $respuesta;
            
            $stmt_reglas = $conexion->prepare("SELECT * FROM reglas_clasificacion WHERE pregunta_id = ?");
            $stmt_reglas->bind_param("i", $idPregunta);
            $stmt_reglas->execute();
            $result_reglas = $stmt_reglas->get_result();
            while ($regla = $result_reglas->fetch_assoc()) {
                // Se pasa la bandera de depuración para ver el detalle de la comparación
                if (evaluarCondicion($respuesta_text, $regla['operador'], $regla['valor'], $debug)) {
                    $stmt_insert = $conexion->prepare("INSERT INTO clasificacion_estudiantes (estudiante_id, grupo, pregunta_id, respuesta) VALUES (?, ?, ?, ?)");
                    // Se asume que: idUsuario y pregunta_id son enteros, grupo y respuesta son cadenas.
                    $stmt_insert->bind_param("isis", $idUsuario, $regla['grupo'], $idPregunta, $respuesta_text);
                    $stmt_insert->execute();
                    $stmt_insert->close();
                }
            }
            $stmt_reglas->close();
        }
    } else {
        // Caso de una sola respuesta (radio button, select, etc.)
// Dentro del foreach (para el caso en que $respuesta no es un array):
    $respuesta_text = (isset($respuestas_otro[$idPregunta]) && trim($respuestas_otro[$idPregunta]) !== '')
    ? $respuestas_otro[$idPregunta]
    : $respuesta;

        $stmt_reglas = $conexion->prepare("SELECT * FROM reglas_clasificacion WHERE pregunta_id = ?");
        $stmt_reglas->bind_param("i", $idPregunta);
        $stmt_reglas->execute();
        $result_reglas = $stmt_reglas->get_result();
        while ($regla = $result_reglas->fetch_assoc()) {
            if (evaluarCondicion($respuesta_text, $regla['operador'], $regla['valor'], $debug)) {
                $stmt_insert = $conexion->prepare("INSERT INTO clasificacion_estudiantes (estudiante_id, grupo, pregunta_id, respuesta) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("isis", $idUsuario, $regla['grupo'], $idPregunta, $respuesta_text);
                $stmt_insert->execute();
                $stmt_insert->close();
            }
        }
        $stmt_reglas->close();
    }
}


////////////////////FIN////////////////////////////





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
    
    // Personalizar el mensaje de redirección
    $mensaje = "¡Redirección exitosa!";
    $mensajeCodificado = urlencode($mensaje . $nombreSeccion);
    header("Location: ../../public/views/encuesta/menu_secciones.php");
    exit;
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
    // Inserción en la tabla "respuestas"
    $stmtUsuarioRespuesta = $conexion->prepare("INSERT INTO respuestas (pregunta_id, estudiante_id, respuesta, created_at) VALUES (?, ?, ?, NOW())");
    if (!$stmtUsuarioRespuesta) {
        echo "Error en prepare(): " . $conexion->error;
    }
    
    $stmtUsuarioRespuesta->bind_param("iis", $idPregunta, $idUsuario, $respuestaTexto);
    
    if (!$stmtUsuarioRespuesta->execute()) {
        echo "Error en execute(): " . $stmtUsuarioRespuesta->error;
    }
    $stmtUsuarioRespuesta->close();

    // Inserción en la tabla "estudiante_respuesta"
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
    $stmtUsuarioRespuesta->close();
}

$conexion->close();
?>