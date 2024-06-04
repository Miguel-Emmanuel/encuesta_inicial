<?php
include("../../../database/conexion.php");
session_start();
if (empty($_SESSION["id"])){
    header("location: ../sesiones/login.php");
    exit;
}
if($_SESSION["id"] != 3){
    header("location: ../sesiones/inicio.php");
    exit;
}

// Consulta SQL para obtener todas las secciones disponibles
$seccionesQuery = $conexion->query("SELECT DISTINCT seccion FROM preguntas");
$secciones = array();
while ($seccion = $seccionesQuery->fetch_assoc()) {
    $secciones[] = $seccion['seccion'];
}

// Paginación: Obtener la sección actual
$seccionActual = isset($_GET['seccion']) ? $_GET['seccion'] : (count($secciones) > 0 ? $secciones[0] : null);

$sql = $conexion->prepare("SELECT * FROM preguntas WHERE seccion = ?");
$sql->bind_param("s", $seccionActual);
$sql->execute();
$resultado = $sql->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Encuesta</title>
    <link rel="stylesheet" href="../../css/encuesta.css">
</head>
<body>
    <div class="container">
        <!-- Título de la sección actual -->
        <h2>Sección: <?php echo $seccionActual; ?></h2>

        <form action="../../../app/Controllers/encuesta_controller.php" method="post" class="form-encuesta">
        <?php
        while ($preguntas = $resultado->fetch_object()) {
            $idPregunta = $preguntas->id;
            $preguntaTexto = $preguntas->pregunta;
            $tipoPregunta = $preguntas->tipo;

            echo "<div class='pregunta'>";
            echo "<p class='pregunta-texto'>$idPregunta. <b>$preguntaTexto</b></p>";

            switch ($tipoPregunta) {
                case 'texto':
                    echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-input' placeholder='Respuesta para la pregunta'>";
                    break;

                    case 'opcion':
                        $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                        if ($opciones_respuesta->num_rows > 0) {
                            echo "<div class='pregunta'>";
                            echo "<p class='pregunta-texto'>Opciones para pregunta ID: $idPregunta</p>";
                            echo "<table>";
                            echo "<tr><th>Opción</th></tr>";
                            while ($opcion = $opciones_respuesta->fetch_object()) {
                                $opcionId = $opcion->id;
                                $nombreOpcion = $opcion->opcion1;
                                echo "<tr><td><input type='radio' name='respuestas[$idPregunta]' value='$opcionId'> $nombreOpcion</td></tr>";
                            }
                            echo "</table>";
                            echo "</div>";
                        } else {
                            echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $idPregunta</p>";
                        }
                        break;
    
                        case 'select':
                            $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                            if ($opciones_respuesta->num_rows > 0) {
                                echo "<select name='respuestas[$idPregunta]' class='respuesta-select'>";
                                while ($opcion = $opciones_respuesta->fetch_object()) {
                                    $opcionId = $opcion->id;
                                    $nombreOpcion = $opcion->opcion1;
                                    echo "<option value='$opcionId'>$nombreOpcion</option>";
                                }
                                echo "</select>";
                            } else {
                                echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $idPregunta</p>";
                            }
                            break;
                            case 'multi':
                                $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                                if ($opciones_respuesta->num_rows > 0) {
                                    echo "<div class='pregunta'>";
                                    echo "<p class='pregunta-texto'>Opciones para pregunta ID: $idPregunta</p>";
                                    $opciones = array();
                                    while ($opcion = $opciones_respuesta->fetch_object()) {
                                        $opciones[$opcion->opcion1][] = $opcion->opcion2;
                                    }
            
                                    if (!empty($opciones)) {
                                        echo "<table>";
                                        echo "<tr><th>Opción 1</th>";
                                        foreach ($opciones[array_key_first($opciones)] as $opcion2) {
                                            echo "<th>$opcion2</th>";
                                        }
                                        echo "</tr>";
            
                                        foreach ($opciones as $opcion1 => $valoresOpcion2) {
                                            echo "<tr>";
                                            echo "<td>$opcion1</td>";
                                            foreach ($valoresOpcion2 as $opcion2) {
                                                echo "<td><input type='checkbox' name='respuestas[$idPregunta][$opcion1][$opcion2]' value='1'></td>";
                                            }
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $idPregunta</p>";
                                    }
                                    echo "</div>";
                                } else {
                                    echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $idPregunta</p>";
                                }
                                break;
            

                default:
                    echo "Tipo de pregunta no soportado";
                    break;
            }
            echo "<input type='hidden' name='preguntas[$idPregunta]' value='$preguntaTexto'>";
            echo "</div>";
        }
        ?>
            <input type="submit" value="Enviar respuestas" class="btn-enviar">
        </form>
        
        <!-- Paginación -->
        <div class="paginacion">
    <?php
    // Mostrar enlaces a las diferentes secciones
    foreach ($secciones as $seccion) {
        // Agregar evento onclick para la validación
        echo "<a href='javascript:void(0);' onclick='cambiarSeccion(\"$seccion\")'>$seccion</a>";
    }
    ?>
</div>

        <a href="../../../app/Controllers/sessiondestroy_controller.php" class="btn-cerrar-sesion">
            <center>
                <input type="submit" name="btningresar" class="btn btn-success" value="Cerrar sesión">
            </center>
        </a>
    </div>

    <script>
    function cambiarSeccion(seccion) {
        // Obtener todos los campos de respuesta de la sección actual
        var inputs = document.querySelectorAll('input[name^="respuestas"]');

        // Verificar si todas las preguntas de la sección actual están completas
        var seccionCompleta = true;
        inputs.forEach(function(input) {
            if (input.value.trim() === "") {
                seccionCompleta = false;
            }
        });

        // Si la sección está completa, permitir el cambio de sección
        if (seccionCompleta) {
            window.location.href = "?seccion=" + encodeURIComponent(seccion);
        } else {
            // Si la sección no está completa, mostrar un mensaje de error
            alert("Por favor complete todas las preguntas de la sección actual antes de cambiar.");
        }
    }
</script>
    <script>
        function enviarRespuestas() {
            var seccionActual = document.getElementById("seccionActual").value;
            var form = document.getElementById("formEncuesta");
            var inputs = form.getElementsByTagName("input");

            var seccionCompleta = true;
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].name.startsWith("respuestas") && inputs[i].value.trim() === "") {
                    seccionCompleta = false;
                    break;
                }
            }

            if (seccionCompleta) {
                // Si la sección está completa, enviar el formulario
                form.submit();
            } else {
                // Si la sección no está completa, mostrar un mensaje de error
                alert("Por favor complete todos los campos de la sección " + seccionActual);
            }
        }
    </script>
</body>
</html>
