<?php
require("../../../database/conexion.php");
session_start();
if (empty($_SESSION["id"])) {
    header("location: ../sesiones/login.php");
    exit;
}
if ($_SESSION["id"] != 3) {
    header("location: ../sesiones/inicio.php");
    exit;
}

// Recibir el parámetro de la sección
$seccion = $_GET['seccion'];

// Consultar las preguntas de la sección desde la base de datos
$sql = $conexion->query("SELECT * FROM preguntas WHERE seccion_id = '$seccion' and depende_p is null");
// Consultar las preguntas de la sección desde la base de datos

// Ejecutar la consulta
$result = $conexion->query("
    SELECT p.*, s.nombre AS seccion_nombre, s.descripcion AS seccion_descripcion
    FROM preguntas p
    INNER JOIN secciones s ON p.seccion_id = s.id
    WHERE p.seccion_id = '$seccion' AND p.depende_p IS NULL
");

// Obtener la primera fila del resultado
$row = $result->fetch_assoc();

// Verificar si se encontraron resultados
if ($row) {
    $seccion_nombre = $row['seccion_nombre'];
    $seccion_descripcion = $row['seccion_descripcion'];
} else {
    // Manejar el caso en que no se encuentran preguntas para la sección dada
    $seccion_nombre = "Sección no encontrada";
    $seccion_descripcion = "";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Encuesta - <?php echo ucfirst($seccion); ?></title>
    <link rel="stylesheet" href="../../css/encuesta.css">
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
      <!-- Incluir jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluir tu script personalizado -->
    <script>
    function obtenerValor(opcion, idPregunta) {
        console.log('El valor del radio button es: ' + opcion);
        console.log('Valor de idPregunta:', idPregunta);

        if (opcion === 'Si') {
            $('#dependientes-' + idPregunta).slideDown();
        } else {
            $('#dependientes-' + idPregunta).slideUp();
        }
    }
</script>
    <style>
        .error-message {
            color: red;
            font-size: 0.875em;
            margin-top: 0.25em;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="col-12 text-center">
            <h1 class="bebas-neue-regular" style="font-size: 100px;">Encuesta Inicial</h1>
        </div>
        <h1>SECCION - <?php echo ucfirst($seccion); ?> <p><?php echo ucfirst($seccion_descripcion); ?></p></h1>
        <div class="col-12">
            <p><strong style="color: red;">*</strong> Indica que la pregunta es obligatoria.</p>
        </div>
        <form id="encuestaForm" action="../../../app/Controllers/encuesta_controller.php" method="post" class="form-encuesta">
            <?php
            while ($preguntas = $sql->fetch_object()) {
                $idPregunta = $preguntas->id;
                $preguntaTexto = $preguntas->pregunta;
                $tipoPregunta = $preguntas->tipo;
                $dependeDe = $preguntas->depende_p;

                echo "<div class='pregunta'>";
                echo "<p class='pregunta-texto'>$idPregunta. <b>$preguntaTexto</b></p>";

                switch ($tipoPregunta) {
                    case 'texto':
                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-input' data-idpregunta='$idPregunta' placeholder='Respuesta para la pregunta'>";
                        break;
                    case 'fecha':
                        echo "<input type='date' name='respuestas[$idPregunta]' class='respuesta-fecha' data-idpregunta='$idPregunta'>";
                        break;
                    case 'correo':
                        echo "<input type='email' name='respuestas[$idPregunta]' class='respuesta-correo' data-idpregunta='$idPregunta' required>";
                        break;
                    case 'curp':
                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-curp' data-idpregunta='$idPregunta'  required>";
                        break;

                    case 'rfc':
                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-rfc' data-idpregunta='$idPregunta' required>";
                        break;
                    case 'numero':
                        echo "<input type='number' name='respuestas[$idPregunta]' class='respuesta-numero' data-idpregunta='$idPregunta' pattern='\d+' required>";
                        break;
                    case 'r_social':
                        echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-r_social' data-idpregunta='$idPregunta' required></textarea>";
                        break;
                    case 'c_postal':
                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-c_postal' data-idpregunta='$idPregunta' required>";
                        break;
                    case 'texto_a':
                        echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-texto_a' data-idpregunta='$idPregunta' required></textarea>";
                        break;

                    case 'opcion':
                        $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                        if ($opciones_respuesta->num_rows > 0) {
                            echo "<div class='pregunta'>";
                            // echo "<p class='pregunta-texto'>Opciones para pregunta ID: $idPregunta</p>";
                            $opciones = array();
                            while ($opcion = $opciones_respuesta->fetch_object()) {
                                $opciones[$opcion->opcion1][] = $opcion->opcion2;
                            }
 
                            if (!empty($opciones)) {
                                echo "<table>";
                                echo "<tr><th></th>";
                                foreach ($opciones[array_key_first($opciones)] as $opcion2) {
                                    echo "<th>$opcion2</th>";
                                }
                                echo "</tr>";

                                foreach ($opciones as $opcion1 => $valoresOpcion2) {
                                    echo "<tr>";
                                    echo "<td>$opcion1</td>";
                                    $cont = 0;
                                    $cont++;

                                    foreach ($valoresOpcion2 as $opcion2) {
                                        echo "<td><input type='radio' class='$idPregunta' name='respuestas[$idPregunta]' id='$cont' value='$opcion1' onclick='obtenerValor(\"$opcion1\", $idPregunta)'></td>";
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
                            // echo "<p class='pregunta-texto'>Opciones para pregunta ID: $idPregunta</p>";
                            $opciones = array();
                            while ($opcion = $opciones_respuesta->fetch_object()) {
                                $opciones[$opcion->opcion1][] = $opcion->opcion2;
                            }

                            if (!empty($opciones)) {
                                echo "<table>";
                                echo "<tr><th></th>";
                                foreach ($opciones[array_key_first($opciones)] as $opcion2) {
                                    echo "<th>$opcion2</th>";
                                }
                                echo "</tr>";
                                $cont = 0;
                                foreach ($opciones as $opcion1 => $valoresOpcion2) {
                                    $cont = $cont + 1;
                                    echo "<tr>";
                                    echo "<td>$opcion1</td>";
                                    foreach ($valoresOpcion2 as $opcion2) {
                                        echo "<td><input type='radio' name='respuestas[$idPregunta][$opcion1]-$cont' value='$opcion2' ></td>";
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
       

                echo "<div class='preguntas-dependientes' id='dependientes-$idPregunta' style='display:none;'>";

                // Consulta para obtener las preguntas dependientes de la pregunta actual
              // Consulta para obtener las preguntas dependientes de la pregunta actual
$sqlDependientes = "SELECT pd.id, pd.pregunta, pd.tipo
FROM preguntas pd
JOIN dependencias_preguntas dp ON pd.id = dp.pregunta_id
WHERE dp.depende_de_pregunta_id = $idPregunta";

                $resultDependientes = $conexion->query($sqlDependientes);

                if ($resultDependientes->num_rows > 0) {
                    // Mostrar las preguntas dependientes encontradas
                    while ($rowDependiente = $resultDependientes->fetch_assoc()) {
                        $idPregunta = $rowDependiente['id'];
                        $textoPreguntaDependiente = $rowDependiente['pregunta'];
                        $pregunta_tipo = $rowDependiente['tipo'];

                        switch ($pregunta_tipo) {
                            case 'texto':
                                echo "<div class='pregunta dependiente' data-pregunta-id='$idPregunta'>";
                                echo "<p class='pregunta-texto'>$idPregunta. <b>$textoPreguntaDependiente</b></p>";
                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-input' data-idpregunta='$idPregunta' placeholder='Respuesta para la pregunta'>";
     
                                echo "</div>";
                                break;
                            case 'fecha':
                                echo "<input type='date' name='respuestas[$idPregunta]' class='respuesta-fecha' data-idpregunta='$idPregunta'>";
                                break;
                            case 'correo':
                                echo "<input type='email' name='respuestas[$idPregunta]' class='respuesta-correo' data-idpregunta='$idPregunta' required>";
                                break;
                            case 'curp':
                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-curp' data-idpregunta='$idPregunta'  required>";
                                break;
        
                            case 'rfc':
                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-rfc' data-idpregunta='$idPregunta' required>";
                                break;
                            case 'numero':
                                echo "<input type='number' name='respuestas[$idPregunta]' class='respuesta-numero' data-idpregunta='$idPregunta' pattern='\d+' required>";
                                break;
                            case 'r_social':
                                echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-r_social' data-idpregunta='$idPregunta' required></textarea>";
                                break;
                            case 'c_postal':
                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-c_postal' data-idpregunta='$idPregunta' required>";
                                break;
                            case 'texto_a':
                                echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-texto_a' data-idpregunta='$idPregunta' required></textarea>";
                                break;
        
                            case 'opcion':
                                $opciones_respuesta = $conexion->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $idPregunta");
                                if ($opciones_respuesta->num_rows > 0) {
                                    echo "<div class='pregunta'>";
                                echo "<p class='pregunta-texto'>$idPregunta. <b>$textoPreguntaDependiente</b></p>";
                                    // echo "<p class='pregunta-texto'>Opciones para pregunta <b>$preguntaTexto</b></p>";
                                    $opciones = array();
                                    while ($opcion = $opciones_respuesta->fetch_object()) {
                                        $opciones[$opcion->opcion1][] = $opcion->opcion2;
                                    }
        
                                    if (!empty($opciones)) {
                                        echo "<table>";
                                        // echo "<tr><th>Opción 1</th>";
                                        foreach ($opciones[array_key_first($opciones)] as $opcion2) {
                                            echo "<th>$opcion2</th>";
                                        }
                                        echo "</tr>";
        
                                        foreach ($opciones as $opcion1 => $valoresOpcion2) {
                                            echo "<tr>";
                                            echo "<td>$opcion1</td>";
                                            $cont = 0;
                                            $cont++;
        
                                            foreach ($valoresOpcion2 as $opcion2) {
                                                echo "<td><input type='radio' class='$idPregunta' name='respuestas[$idPregunta]' value='$opcion1' ></td>";
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
                                echo "<p class='pregunta-texto'>$idPregunta. <b>$textoPreguntaDependiente</b></p>";
                                    // echo "<p class='pregunta-texto'>Opciones para pregunta ID: $idPregunta</p>";
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
                                        $cont = 0;
                                        foreach ($opciones as $opcion1 => $valoresOpcion2) {
                                            $cont = $cont + 1;
                                            echo "<tr>";
                                            echo "<td>$opcion1</td>";
                                            foreach ($valoresOpcion2 as $opcion2) {
                                                echo "<td><input type='radio' name='respuestas[$idPregunta][$opcion1]-$cont' value='$opcion2' ></td>";
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
               
                    }
                } else {
                    // echo "No hay preguntas dependientes para esta pregunta.";
                }

                echo "</div>"; // Cierre de preguntas-dependientes
                echo "<input type='hidden' name='preguntas[$idPregunta]' value='$preguntaTexto'>";

                echo "</div>"; // Cierre de pregunta principal
            }
      

        // Cerrar conexión
        // $conn->close();
                // echo "</div>"; // Cierre de preguntas-dependientes
                // // echo "</div>"; // Cierre de pregunta principal
                // echo "<input type='hidden' name='preguntas[$idPregunta]' value='$preguntaTexto'>";
                // echo "</div>";
            
            ?>
            <center>
                <div id="alert-container"></div>
                <input type="submit" value="Enviar respuestas" class="btn-enviar">
            </center>
        </form>

        <a href="../../../app/Controllers/sessiondestroy_controller.php" class="btn-cerrar-sesion">
            <center>
                <input type="submit" name="btningresar" class="btn btn-success" value="Cerrar sesión">
            </center>
        </a>

    </div>
    <script>
        document.getElementById('encuestaForm').addEventListener('submit', function(event) {
            var form = event.target;
            var alertContainer = document.getElementById('alert-container');
            var valid = true;
            var errorMessages = [];

            // Limpiar contenedor de alertas
            alertContainer.innerHTML = '';
            var errorElements = form.querySelectorAll('.error-message');
            errorElements.forEach(function(el) {
                el.remove();
            });

            // Validar campos específicos
            var rfcInputs = form.querySelectorAll('input.respuesta-rfc');
            rfcInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateRFC(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':RFC no es válido. Debe seguir el formato correcto.';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                }
            });

            var curpInputs = form.querySelectorAll('input.respuesta-curp');
            curpInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateCURP(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ': CURP no es válido. Debe seguir el formato correcto.';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                }
            });


            var emailInputs = form.querySelectorAll('input.respuesta-correo');
            emailInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateEmail(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':Correo no es válido. Debe seguir el formato correcto.';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                }
            });

            var numberInputs = form.querySelectorAll('input.respuesta-numero');
            numberInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateNumber(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':Número no es válido. Debe contener solo dígitos.';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                }
            });

            var socialInputs = form.querySelectorAll('textarea.respuesta-r_social');
            socialInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateURL(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':URL no es válida. Debe seguir el formato correcto.';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                }
            });

            var postalInputs = form.querySelectorAll('input.respuesta-c_postal');
            postalInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validatePostalCode(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':Código postal no es válido. Debe contener solo 5 dígitos.';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                }
            });

            if (!valid) {
                event.preventDefault();
                var alert = createAlert(errorMessages.join('<br>'));
                alertContainer.appendChild(alert);
            }
        });

        function validateRFC(rfc) {
            var rfcPattern = /^([A-ZÑ&]{3,4})\d{2}(0[1-9]|1[0-2])([0-2][1-9]|3[0-1])[A-Z\d]{2}[A\d]$/;
            return rfcPattern.test(rfc);
        }

        function validateCURP(curp) {
            var curpPattern = /^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z\d]{2}$/;
            return curpPattern.test(curp);
        }

        function validateEmail(email) {
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }

        function validateNumber(number) {
            var numberPattern = /^\d+$/;
            return numberPattern.test(number);
        }

        function validateURL(url) {
            var urlPattern = /^(http|https):\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(:[0-9]{1,5})?(\/.*)?$/;
            return urlPattern.test(url);
        }

        function validatePostalCode(postalCode) {
            var postalCodePattern = /^\d{5}$/;
            return postalCodePattern.test(postalCode);
        }

        function createErrorElement(message) {
            var errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.innerText = message;
            return errorElement;
        }

        function createAlert(message) {
            var alert = document.createElement('div');
            alert.className = 'alert alert-danger';
            alert.role = 'alert';
            alert.innerHTML = '<h4 class="alert-heading">Error en el formulario</h4>' +
                '<p>' + message + '</p>' +
                '<hr />' +
                '<p class="mb-0">Por favor corrige los errores y vuelve a intentarlo.</p>';
            return alert;
        }




    </script>

</body>

</html>