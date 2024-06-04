<?php
include("../../../database/conexion.php");
session_start();
if (empty($_SESSION["id"])) {
    header("location: ../sesiones/login.php");
    exit;
}
if ($_SESSION["id"] != 3) {
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
    <title>Encuesta Inicial | Inicio</title>
    <link rel="stylesheet" href="../../../bootstrap/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/letters.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Oswald:wght@200..700&family=Passion+One:wght@400;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row m-5">
            <div class="col-12 text-center">
                <h1 class="bebas-neue-regular" style="font-size: 100px;">Encuesta Inicial</h1>
            </div>
            <div class="col-12">
                <p><strong style="color: red;">*</strong> Indica que la pregunta es obligatoria.</p>
            </div>
            <form action="../../../app/Controllers/encuesta_controller.php" method="post" style="padding: 0px;">
                <div class='col-12 row shadow p-3 mb-5 bg-body-tertiary rounded'>
                    <!-- Título de la sección actual -->
                    <div class="col-12 p-3">
                        <h4 class="oswald-secondary">Datos <?php echo $seccionActual; ?></h4>
                    </div>

                    <?php
                    while ($preguntas = $resultado->fetch_object()) {
                        $idPregunta = $preguntas->id;
                        $preguntaTexto = $preguntas->pregunta;
                        $tipoPregunta = $preguntas->tipo;

                        switch ($tipoPregunta) {
                            case 'texto':
                                echo "<div class='col-4 mt-3'>";
                                echo "<label for='respuestas[$idPregunta]' class='form-label'><strong style='color: red;'>*</strong> $idPregunta. $preguntaTexto:</label>";
                                echo "<input type='text' name='respuestas[$idPregunta]' id='respuestas[$idPregunta]' class='form-control' placeholder='Ingrese su respuesta...'>";
                                echo "</div>";
                                break;

                            case 'opcion':
                                $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                                if ($opciones_respuesta->num_rows > 0) {
                                    echo "<div class='col-6 mt-4'>";
                                    echo "<label for='respuestas[$idPregunta]' class='form-label'><strong style='color: red;'>*</strong> $idPregunta. $preguntaTexto:</label>";
                                    echo "<p><strong>Opciones</strong></p>";
                                    while ($opcion = $opciones_respuesta->fetch_object()) {
                                        $opcionId = $opcion->id;
                                        $nombreOpcion = $opcion->opcion1;
                                        echo "<div class='form-check'>";
                                        echo "<input class='form-check-input' type='radio' name='respuestas[$idPregunta]' id='respuestas[$nombreOpcion]' value='$opcionId'>";
                                        echo "<label class='form-check-label' for='respuestas[$nombreOpcion]'>$nombreOpcion</label>";
                                        echo "</div>";
                                    }
                                    echo "<div id='respuestas[$idPregunta]' class='form-text pt-1'>Opciones para pregunta ID: $idPregunta. </div>";
                                    echo "</div>";
                                } else {
                                    echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $idPregunta</p>";
                                }
                                break;

                            case 'select':
                                $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                                if ($opciones_respuesta->num_rows > 0) {
                                    echo "<div class='col-12 mt-3 justify-content-center'>";
                                    echo "<label for='respuestas[$idPregunta]' class='form-label'><strong style='color: red;'>*</strong> $idPregunta. $preguntaTexto:</label>";
                                    echo "<select name='respuestas[$idPregunta]' class='form-select form-select-lg mb-3' id='respuestas[$idPregunta]'>";
                                    while ($opcion = $opciones_respuesta->fetch_object()) {
                                        $opcionId = $opcion->id;
                                        $nombreOpcion = $opcion->opcion1;
                                        echo "<option value='$opcionId'>$nombreOpcion</option>";
                                    }
                                    echo "</select>";
                                    echo "</div>";
                                } else {
                                    echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $idPregunta</p>";
                                }
                                break;
                            case 'multi':
                                $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                                if ($opciones_respuesta->num_rows > 0) {
                                    echo "<div class='col-12 mt-4'>";
                                    echo "<label for='respuestas[$idPregunta]' class='form-label'><strong style='color: red;'>*</strong> $idPregunta. $preguntaTexto:</label>";
                                    echo "<div class='pregunta'>";
                                    $opciones = array();
                                    while ($opcion = $opciones_respuesta->fetch_object()) {
                                        $opciones[$opcion->opcion1][] = $opcion->opcion2;
                                    }

                                    if (!empty($opciones)) {
                                        echo "<table>";
                                        echo "<tr><th>Opciones</th>";
                                        foreach ($opciones[array_key_first($opciones)] as $opcion2) {
                                            echo "<th class='text-center'>$opcion2 &nbsp;</th>";
                                        }
                                        echo "</tr>";

                                        foreach ($opciones as $opcion1 => $valoresOpcion2) {
                                            echo "<tr>";
                                            echo "<td>$opcion1</td>";
                                            foreach ($valoresOpcion2 as $opcion2) {
                                                echo "<td class='text-center'><input class='form-check-input' type='checkbox' id='respuestas[$idPregunta]' name='respuestas[$idPregunta][$opcion1][$opcion2]' value='1'></td>";
                                            }
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $idPregunta</p>";
                                    }
                                    echo "<div id='respuestas[$idPregunta]' class='form-text pt-1'>Opciones para pregunta ID: $idPregunta. </div>";
                                    echo "</div>";
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
                    }
                    ?>
                    <div class="col-12 pt-3 text-center">
                        <input type="submit" value="Enviar respuestas" class="btn btn-success">
                    </div>
                </div>
            </form>

            <!-- Paginación -->
            <div class="col-12 mt-2 d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Anterior</a></li>
                        <?php
                        $index = 1;
                        // Mostrar enlaces a las diferentes secciones
                        foreach ($secciones as $seccion) {
                            // Agregar evento onclick para la validación
                            echo "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='cambiarSeccion(\"$seccion\")'>$index</a></li>";
                            $index++;
                        }
                        ?>
                        <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <a href="../../../app/Controllers/sessiondestroy_controller.php">
            <center><input type="submit" name="btningresar" class="btn btn-success" value="Cerrar sesión"></center>
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