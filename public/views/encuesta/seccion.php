<?php
require("../../../database/conexion.php");

session_start();
if (empty($_SESSION["id"])) {
    header("location: ../sesiones/login.php");
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
    <title>Entrevista - <?php echo ucfirst($seccion); ?></title>
    <link rel="stylesheet" href="../../css/seccion.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrevista Inicial | Inicio</title>
    <link rel="stylesheet" href="../../../bootstrap/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../../css/letters.css"> -->
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

            var campoOtro = $('#campo_otro_' + idPregunta); // Campo de texto "Otro"
            var dependientes = $('#dependientes-' + idPregunta); // Preguntas dependientes

            // Convertir 'opcion' a minúsculas para simplificar comparaciones
            var opcionNormalizada = opcion.toLowerCase();

            /////// Desplegar preguntas dependientes si la opción es "Si" ///////
            if (opcion === 'Si') {
                dependientes.slideDown(); // Mostrar preguntas dependientes si se selecciona "Si"
                campoOtro.slideUp(); // Asegurarse de que el campo 'Otro' esté oculto si se selecciona "Si"
                campoOtro.find('input').prop('required', false); // Hacer opcional el campo
                console.log("Preguntas dependientes desplegadas");
            } else {
                dependientes.slideUp(); // Ocultar preguntas dependientes si se selecciona otra opción

                if (opcion === 'Otro:' || opcionNormalizada === 'si') {
                    campoOtro.slideDown(); // Mostrar el campo de texto "Otro:"
                    campoOtro.find('input').prop('required', true); // Hacer obligatorio el campo
                    console.log("Campo 'Otro' desplegado");
                    if(idPregunta === 73){
                        campoOtro.slideDown(); // Mostrar el campo de texto "Otro:"
                    campoOtro.find('input').prop('required', true); // Hacer obligatorio el campo
                    console.log("Campo 'Otro' desplegado");
                    }
                } else {
                    campoOtro.slideUp(); // Ocultar el campo de texto si se selecciona cualquier otra opción
                    campoOtro.find('input').prop('required', false); // Hacer opcional el campo
                    campoOtro.find('input').val(''); // Limpiar el valor del campo cuando se oculta
                    console.log("Campo 'Otro' oculto");
                }
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
            <h1 class="bebas-neue-regular" style="font-size: 100px;">Entrevista Inicial</h1>
        </div>
        <h1> <?php echo ucfirst($seccion_descripcion); ?> <p> SECCIÓN - <?php echo ucfirst($seccion); ?></p>
        </h1>

        <form id="encuestaForm" action="../../../app/Controllers/encuesta_controller.php" method="post" class="form-encuesta" onsubmit="limpiarStorage();">
            <?php

            function obtenerRespuestasUsuario($conexion, $idUsuario)
            {
                $stmt = $conexion->prepare("SELECT pregunta_id, respuesta_texto FROM estudiante_respuesta WHERE estudiante_id = ?");
                $stmt->bind_param("i", $idUsuario);
                $stmt->execute();
                $result = $stmt->get_result();
                $respuestas = array();

                while ($row = $result->fetch_assoc()) {
                    $respuestas[$row['pregunta_id']] = $row['respuesta_texto'];
                }

                $stmt->close();
                return $respuestas;
            }



            $idUsuario = $_SESSION["id"];
            $respuestasUsuario = obtenerRespuestasUsuario($conexion, $idUsuario);

            // $idPregunta = 1; // ID de la pregunta, esto normalmente vendría de la base de datos

            $mostrarTituloNacimiento = true;

            while ($preguntas = $sql->fetch_object()) {
                $idPregunta = $preguntas->id;
                $preguntaTexto = $preguntas->pregunta;
                $tipoPregunta = $preguntas->tipo;
                $dependeDe = $preguntas->depende_p;
                $respuestaTexto = isset($respuestasUsuario[$idPregunta]) ? htmlspecialchars($respuestasUsuario[$idPregunta]) : '';
                $ayu = $preguntas->ayuda;

                echo "<div class='pregunta'>";
                if ($mostrarTituloNacimiento && in_array($idPregunta, [16, 17, 18, 19])) {
                    echo "<br><h3>Datos de Nacimiento</h3><br>";
                    $mostrarTituloNacimiento = false; // Cambiar a false para no volver a mostrar el título

                }

                // Mostrar el título "Fin de Sección Nacimiento" al llegar a la pregunta 20
                if ($idPregunta == 20) {
                    echo "<br><h3>Datos Personales</h3><br>";
                }

                echo "<p class='pregunta-texto'>$idPregunta. <b>$preguntaTexto</b>";
                if ($ayu !== null) {
echo "<button type='button' class='btn btn-secondary btn-sm rounded-pill' 
        data-bs-toggle='tooltip' 
        style='margin-left: 10px;'  
        data-bs-placement='top' 
        data-bs-title='Tooltip on top' 
        title='[$ayu]' 
        id='tooltipButton'>
        !
      </button>";
                }
                echo "</p>";





                switch ($tipoPregunta) {
                    case 'texto':

                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-input' data-idpregunta='$idPregunta' placeholder='' value='$respuestaTexto' required>";
                        break;
                    case 'fecha':
                        echo "<input type='date' name='respuestas[$idPregunta]' class='respuesta-fecha' data-idpregunta='$idPregunta' value='$respuestaTexto' required>";
                        break;
                    case 'correo':
                        echo "<input type='email' name='respuestas[$idPregunta]' class='respuesta-correo' data-idpregunta='$idPregunta' value='$respuestaTexto' required>";
                        break;
                    case 'curp':
                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-curp' data-idpregunta='$idPregunta' value='$respuestaTexto'  required>";
                        break;

                    case 'rfc':
                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-rfc' data-idpregunta='$idPregunta' value='$respuestaTexto' required>";
                        break;
                    case 'telefono':
                        echo "<input type='number' name='respuestas[$idPregunta]' class='respuesta-telefono' data-idpregunta='$idPregunta' value='$respuestaTexto' pattern='\d+' required>";
                        break;
                    case 'numero':
                        echo "<input type='number' name='respuestas[$idPregunta]' class='respuesta-numero' data-idpregunta='$idPregunta' value='$respuestaTexto'  required>";
                        break;
                    case 'r_social':
                        echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-r_social' data-idpregunta='$idPregunta' value='$respuestaTexto' required></textarea>";
                        break;
                    case 'c_postal':
                        echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-c_postal' data-idpregunta='$idPregunta' value='$respuestaTexto' required>";
                        break;
                    case 'texto_a':
                        echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-texto_a' data-idpregunta='$idPregunta' value='$respuestaTexto' required></textarea>";
                        break;
                    case 'genero':
                        $generos = $conexion->query("SELECT id, nombreig as nombre FROM i_genero");
                        echo "<div class='select-container'>";
                        echo "<select name='respuestas[$idPregunta]' id='genero-select-$idPregunta' class='respuesta-genero' data-idpregunta='$idPregunta' required>";
                        echo "<option value=''>Seleccione su género</option>";
                        while ($genero = $generos->fetch_assoc()) {
                            $selected = ($respuestaTexto == $genero['nombre']) ? 'selected' : '';
                            echo "<option value='{$genero['nombre']}' $selected>{$genero['nombre']}</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                        break;

                    case 'pais':
                        echo "<div class='select-container'>";
                        echo "<select name='respuestas[$idPregunta]' id='pais_$idPregunta' class='respuesta-pais' onchange='cargarEstados(this, $idPregunta)' required>";
                        echo "<option value=''>Seleccione su país</option>";

                        $resultPais = $conexion->query("SELECT * FROM paises");
                        while ($pais = $resultPais->fetch_assoc()) {
                            echo "<option value='{$pais['id']},{$pais['nombre']}'>{$pais['nombre']}</option>";
                        }
                        echo "<option value='otro'>Otro</option>";
                        echo "</select>";
                        echo "</div>";
                        break;

                    case 'estado':
                        echo "<div class='select-container'>";
                        echo "<select name='respuestas[$idPregunta]' id='estado_$idPregunta' class='respuesta-estado' onchange='cargarMunicipios(this, $idPregunta)' required>";
                        echo "<option value=''>Seleccione su estado</option>";
                        echo "</select>";
                        echo "</div>";
                        break;

                    case 'municipio':
                        echo "<div class='select-container'>";
                        echo "<select name='respuestas[$idPregunta]' id='municipio_$idPregunta' class='respuesta-municipio' required>";
                        echo "<option value=''>Seleccione su municipio</option>";
                        echo "</select>";
                        echo "</div>";
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
                                echo "<table >";
                                echo "<tr><th></th>";
                                foreach ($opciones[array_key_first($opciones)] as $opcion2) {
                                    echo "<th >$opcion2</th>";
                                }
                                echo "</tr>";

                                foreach ($opciones as $opcion1 => $valoresOpcion2) {
                                    echo "<tr>";
                                    echo "<td class='hola'>$opcion1</td>";
                                    $cont = 0;

                                    foreach ($valoresOpcion2 as $opcion2) {
                                        $cont++;

                                        // Verifica si la opción seleccionada anteriormente es igual al valor del radio actual
                                        $checked = ($respuestaTexto == $opcion1) ? 'checked' : '';
                                        echo "<td ><input type='radio' class='$idPregunta' name='respuestas[$idPregunta]' id='$cont' value='$opcion1' onchange='obtenerValor(\"$opcion1\", $idPregunta)' data-idpregunta='$idPregunta' required></td>";
                                    }

                                    echo "</tr>";
                                }
                                // Aquí se genera el campo oculto que aparecerá solo si selecciona "Otro:"
                                echo "<div class='container-dinamico'>";
                                echo "<tr id='campo_otro_$idPregunta'  style='display:none;'>
                                        <td colspan='2'>
                                       <label for='otro_texto'>Especifica:</label>
                                      <!-- Cambiar el name para que contenga 'otro' además del idPregunta -->
                                                <input type='text' id='otro_texto_$idPregunta' name='respuestas_otro[$idPregunta]' value='$respuestaTexto' data-idpregunta='$idPregunta'>
                                            </td>
                                        </tr>
                                        </div>
                                        ";
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
                            echo "<div class='select-container'>";
                            echo "<select name='respuestas[$idPregunta]' class='respuesta-select' data-idpregunta='$idPregunta'>";
                            echo "<option value=''>Seleccione una opción</option>";
                            while ($opcion = $opciones_respuesta->fetch_object()) {
                                $opcionId = $opcion->id;
                                $nombreOpcion = $opcion->opcion1;
                                $selected = ($opcionId == $respuestaTexto) ? 'selected' : '';
                                echo "<option value='$opcionId' $selected >$nombreOpcion</option>";
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
                                    echo "<td class='hola'>$opcion1</td>";
                                    foreach ($valoresOpcion2 as $opcion2) {

                                        $radioId = "custom-radio-$idPregunta-$cont-" . md5($opcion2);
                                        echo "
                                        <td class='espaciadoo'>
                                            <input type='radio' id='$radioId' class='custom-radio' name='respuestas[$idPregunta][$opcion1]-$cont' value='$opcion2' data-idpregunta='$idPregunta-$cont-$opcion2' onchange='obtenerValor(\"$opcion1\", $idPregunta)'>
                                            <label for='$radioId' class='custom-radio-label'></label>
                                        </td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "<div class='container-dinamico'>";
                                echo "<tr id='campo_otro_$idPregunta'  style='display:none;'>
                                        <td colspan='2'>
                                       <label for='otro_texto'>Especifica:</label>
                                      <!-- Cambiar el name para que contenga 'otro' además del idPregunta -->
                                                <input type='text' id='otro_texto_$idPregunta' name='respuestas_otro[$idPregunta]' value='$respuestaTexto' data-idpregunta='$idPregunta'>
                                            </td>
                                        </tr>
                                        </div>
                                        ";
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
                $sqlDependientes = "SELECT pd.id, pd.pregunta, pd.tipo, pd.ayuda
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
                        $ayu = $rowDependiente['ayuda'];



                        echo "<div class='pregunta dependiente' data-pregunta-id='$idPregunta'>";
                        echo "<p class='pregunta-texto'>$idPregunta. <b>$textoPreguntaDependiente</b>";
                        if ($ayu !== null) {
                            echo "<button type='button' class='btn btn-secondary btn-sm rounded-pill' data-bs-toggle='tooltip' style='margin-left: 10px;'  data-bs-placement='top' data-bs-title='Tooltip on top' title='[$ayu]'>!</button>";
                            echo "</p>";
                        }
                        echo "</div>";
                        switch ($pregunta_tipo) {
                            case 'texto':

                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-input' data-idpregunta='$idPregunta' placeholder='' value='$respuestaTexto' >";
                                break;
                            case 'fecha':
                                echo "<input type='date' name='respuestas[$idPregunta]' class='respuesta-fecha' data-idpregunta='$idPregunta' value='$respuestaTexto' >";
                                break;
                            case 'correo':
                                echo "<input type='email' name='respuestas[$idPregunta]' class='respuesta-correo' data-idpregunta='$idPregunta' value='$respuestaTexto' >";
                                break;
                            case 'curp':
                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-curp' data-idpregunta='$idPregunta' value='$respuestaTexto'  >";
                                break;

                            case 'rfc':
                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-rfc' data-idpregunta='$idPregunta' value='$respuestaTexto' >";
                                break;
                            case 'telefono':
                                echo "<input type='number' name='respuestas[$idPregunta]' class='respuesta-telefono' data-idpregunta='$idPregunta' value='$respuestaTexto' pattern='\d+' >";
                                break;
                            case 'numero':
                                echo "<input type='number' name='respuestas[$idPregunta]' class='respuesta-numero' data-idpregunta='$idPregunta' value='$respuestaTexto'  >";
                                break;
                            case 'r_social':
                                echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-r_social' data-idpregunta='$idPregunta' value='$respuestaTexto' ></textarea>";
                                break;
                            case 'c_postal':
                                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-c_postal' data-idpregunta='$idPregunta' value='$respuestaTexto' >";
                                break;
                            case 'texto_a':
                                echo "<textarea type='text' name='respuestas[$idPregunta]' class='respuesta-texto_a' data-idpregunta='$idPregunta' value='$respuestaTexto' ></textarea>";
                                break;
                            case 'genero':
                                $generos = $conexion->query("SELECT id, nombreig as nombre FROM i_genero");
                                echo "<div class='select-container'>";
                                echo "<select name='respuestas[$idPregunta]' id='genero-select-$idPregunta' class='respuesta-genero' data-idpregunta='$idPregunta' >";
                                echo "<option value=''>Seleccione su género</option>";
                                while ($genero = $generos->fetch_assoc()) {
                                    $selected = ($respuestaTexto == $genero['nombre']) ? 'selected' : '';
                                    echo "<option value='{$genero['nombre']}' $selected>{$genero['nombre']}</option>";
                                }
                                echo "</select>";
                                echo "</div>";
                                break;

                            case 'pais':
                                echo "<div class='select-container'>";
                                echo "<select name='respuestas[$idPregunta]' id='pais_$idPregunta' class='respuesta-pais' onchange='cargarEstados(this, $idPregunta)' >";
                                echo "<option value=''>Seleccione su país</option>";

                                $resultPais = $conexion->query("SELECT * FROM paises");
                                while ($pais = $resultPais->fetch_assoc()) {
                                    echo "<option value='{$pais['id']},{$pais['nombre']}'>{$pais['nombre']}</option>";
                                }
                                echo "<option value='otro'>Otro</option>";
                                echo "</select>";
                                echo "</div>";
                                break;

                            case 'estado':
                                echo "<div class='select-container'>";
                                echo "<select name='respuestas[$idPregunta]' id='estado_$idPregunta' class='respuesta-estado' onchange='cargarMunicipios(this, $idPregunta)' >";
                                echo "<option value=''>Seleccione su estado</option>";
                                echo "</select>";
                                echo "</div>";
                                break;

                            case 'municipio':
                                echo "<div class='select-container'>";
                                echo "<select name='respuestas[$idPregunta]' id='municipio_$idPregunta' class='respuesta-municipio' >";
                                echo "<option value=''>Seleccione su municipio</option>";
                                echo "</select>";
                                echo "</div>";
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
                                            echo "<td class='hola'>$opcion1</td>";
                                            $cont = 0;

                                            foreach ($valoresOpcion2 as $opcion2) {
                                                $cont++;

                                                // Verifica si la opción seleccionada anteriormente es igual al valor del radio actual
                                                $checked = ($respuestaTexto == $opcion1) ? 'checked' : '';
                                                echo "<td><input type='radio' class='$idPregunta' name='respuestas[$idPregunta]' id='$cont' value='$opcion1' onchange='obtenerValor(\"$opcion1\", $idPregunta)' data-idpregunta='$idPregunta' ></td>";
                                            }

                                            echo "</tr>";
                                        }
                                        // Aquí se genera el campo oculto que aparecerá solo si selecciona "Otro:"
                                        echo "<div class='container-dinamico'>";
                                        echo "<tr id='campo_otro_$idPregunta'  style='display:none;'>
                                                <td colspan='2'>
                                               <label for='otro_texto'>Especifica:</label>
                                              <!-- Cambiar el name para que contenga 'otro' además del idPregunta -->
                                                        <input type='text' id='otro_texto_$idPregunta' name='respuestas_otro[$idPregunta]' value='$respuestaTexto' data-idpregunta='$idPregunta'>
                                                    </td>
                                                </tr>
                                                </div>
                                                ";
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
                                    echo "<div class='select-container'>";
                                    echo "<select name='respuestas[$idPregunta]' class='respuesta-select' data-idpregunta='$idPregunta'>";
                                    echo "<option value=''>Seleccione una opción</option>";
                                    while ($opcion = $opciones_respuesta->fetch_object()) {
                                        $opcionId = $opcion->id;
                                        $nombreOpcion = $opcion->opcion1;
                                        $selected = ($opcionId == $respuestaTexto) ? 'selected' : '';
                                        echo "<option value='$opcionId' $selected >$nombreOpcion</option>";
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
                                            echo "<td class='hola'>$opcion1</td>";
                                            foreach ($valoresOpcion2 as $opcion2) {
                                                $radioId = "custom-radio-$idPregunta-$cont-" . md5($opcion2);
                                                echo "<td>
                                                        <input type='radio' id='$radioId' class='custom-radio' name='respuestas[$idPregunta][$opcion1]-$cont' value='$opcion2' data-idpregunta='$idPregunta-$cont-$opcion2'>
                                                        <label for='$radioId' class='custom-radio-label'></label>
                                                      </td>";
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

        </form> <!-- Asegúrate de que esta etiqueta cierre correctamente tu formulario -->

        <div class="botones">
            <a href="menu_secciones.php" class="btn-menu" onclick="return confirmarRegreso();">Regresar</a>
            <a href="../../../app/Controllers/sessiondestroy_controller.php" class="btn-cerrar-sesion" onclick="return confirmarLogout();">Cerrar sesión</a>
        </div>

    </div>
    <script>
        ////////////////////////////////BOTON DE REGRESAR |ALERTA|///////////////////////
        function confirmarRegreso() {
            return confirm("¿Estás seguro de regresar al menú?");
        }

        function confirmarLogout() {
            return confirm("¿Estás seguro de cerrar sesión?");
        }

        ////////////////////////////////////////////////////////////////////////////////////
        //////////////VALIDACION DE CAMPOS Y MUESTRA DE ERRORES ///////////////////////
    // Agregar evento de validación en submit
document.getElementById('encuestaForm').addEventListener('submit', function(event) {
    var form = event.target;
    var alertContainer = document.getElementById('alert-container');
    var valid = true;
    var errorMessages = [];
    var firstErrorElement = null; // Para hacer scroll hacia la primera pregunta con error

    // Limpiar contenedor de alertas
    alertContainer.innerHTML = '';
    
    // Eliminar mensajes de error previos
    var errorElements = form.querySelectorAll('.error-message');
    errorElements.forEach(function(el) {
        el.remove();
    });

    // Limpiar clases de error visual
    var preguntaElements = form.querySelectorAll('.pregunta');
    preguntaElements.forEach(function(pregunta) {
        pregunta.classList.remove('error'); // Remueve el borde rojo u otra clase de error
    });


    
            // Validar campos específicos
            var rfcInputs = form.querySelectorAll('input.respuesta-rfc');
            rfcInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateRFC(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':RFC no es válido. Debe seguir el formato correcto.  Ejemplo: VECJ8803268V0';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                    // Resaltar la pregunta en rojo (añadiendo una clase de error)
                    var preguntaElement = input.closest('.pregunta');
                    if (preguntaElement) {
                        preguntaElement.classList.add('error');

                        // Si es la primera pregunta con error, guardarla para hacer scroll
                        if (!firstErrorElement) {
                            firstErrorElement = preguntaElement;
                        }
                    }
                }
            });

            var curpInputs = form.querySelectorAll('input.respuesta-curp');
            curpInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateCURP(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ': CURP no es válido. Debe seguir el formato correcto.  Ejemplo: RACW050729MMCSHNA2';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                    // Resaltar la pregunta en rojo (añadiendo una clase de error)
                    var preguntaElement = input.closest('.pregunta');
                    if (preguntaElement) {
                        preguntaElement.classList.add('error');

                        // Si es la primera pregunta con error, guardarla para hacer scroll
                        if (!firstErrorElement) {
                            firstErrorElement = preguntaElement;
                        }
                    }
                }
            });


            var emailInputs = form.querySelectorAll('input.respuesta-correo');
            emailInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateEmail(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':Correo no es válido. Debe seguir el formato correcto.  Ejemplo: usuario@gmail.com ';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                    // Resaltar la pregunta en rojo (añadiendo una clase de error)
                    var preguntaElement = input.closest('.pregunta');
                    if (preguntaElement) {
                        preguntaElement.classList.add('error');

                        // Si es la primera pregunta con error, guardarla para hacer scroll
                        if (!firstErrorElement) {
                            firstErrorElement = preguntaElement;
                        }
                    }
                }
            });

            var numberInputs = form.querySelectorAll('input.respuesta-telefono');
            numberInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateNumber(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':Número no es válido. Debe contener solo dígitos. (10)';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                    // Resaltar la pregunta en rojo (añadiendo una clase de error)
                    var preguntaElement = input.closest('.pregunta');
                    if (preguntaElement) {
                        preguntaElement.classList.add('error');

                        // Si es la primera pregunta con error, guardarla para hacer scroll
                        if (!firstErrorElement) {
                            firstErrorElement = preguntaElement;
                        }
                    }
                }
            });

            var socialInputs = form.querySelectorAll('textarea.respuesta-r_social');
            socialInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validateURL(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':URL no es válida. Debe seguir el formato correcto. Ej. "https://www.facebook.com/profile"';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                    // Resaltar la pregunta en rojo (añadiendo una clase de error)
                    var preguntaElement = input.closest('.pregunta');
                    if (preguntaElement) {
                        preguntaElement.classList.add('error');

                        // Si es la primera pregunta con error, guardarla para hacer scroll
                        if (!firstErrorElement) {
                            firstErrorElement = preguntaElement;
                        }
                    }
                }
            });

            var postalInputs = form.querySelectorAll('input.respuesta-c_postal');
            postalInputs.forEach(function(input) {
                var idPregunta = input.getAttribute('data-idpregunta');
                if (input.value !== '' && !validatePostalCode(input.value)) {
                    valid = false;
                    var errorMessage = 'Pregunta #' + idPregunta + ':Código postal no es válido. Debe contener solo 5 dígitos.  Ejemplo: 52050';
                    errorMessages.push(errorMessage);
                    var errorElement = createErrorElement(errorMessage);
                    input.parentElement.appendChild(errorElement);
                    // Resaltar la pregunta en rojo (añadiendo una clase de error)
                    var preguntaElement = input.closest('.pregunta');
                    if (preguntaElement) {
                        preguntaElement.classList.add('error');

                        // Si es la primera pregunta con error, guardarla para hacer scroll
                        if (!firstErrorElement) {
                            firstErrorElement = preguntaElement;
                        }
                    }
                }
            });

        // Si el formulario no es válido, evitar que se envíe y mostrar alerta
    if (!valid) {
        event.preventDefault();

        // Mostrar alerta general en el contenedor de alertas
        var alert = createAlert('Algunas preguntas tienen errores. Por favor corrígelos.');
        alertContainer.appendChild(alert);

        // Hacer scroll hacia la primera pregunta con error
        if (firstErrorElement) {
            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
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
            var numberPattern = /^\d{10}$/;
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

    // Crear el mensaje de error debajo de una pregunta
function createErrorElement(message) {
    var errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.innerText = message;
    return errorElement;
}

// Crear una alerta general en la parte superior del formulario
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

        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[data-idpregunta], textarea[data-idpregunta], select[data-idpregunta]');
            const form = document.querySelector('form'); // Asegúrate de que tu formulario tenga la etiqueta <form>

            // Cargar valores de localStorage
            inputs.forEach(function(input) {
                let storedValue = localStorage.getItem('respuesta_' + input.dataset.idpregunta);

                if (storedValue) {
                    if (input.type === 'radio') {
                        // Marcar el radio como 'checked' si su valor coincide con el almacenado
                        if (input.value === storedValue) {
                            input.checked = true; // Asegúrate de que el radio esté seleccionado
                            obtenerValor(storedValue, input.dataset.idpregunta); // Ejecuta la función para desplegar el campo dinámico
                        }
                    } else {
                        // Restaurar otros tipos de input (text, select, etc.)
                        input.value = storedValue;
                    }
                }

                // Guardar el valor en localStorage cuando el usuario interactúe
                input.addEventListener('input', function() {
                    if (input.type === 'radio') {
                        // Para radios, almacenar el valor cuando se selecciona
                        if (input.checked) {
                            localStorage.setItem('respuesta_' + input.dataset.idpregunta, input.value);
                            obtenerValor(input.value, input.dataset.idpregunta); // Llama a la función al seleccionar
                        }
                    } else {
                        // Para otros inputs
                        localStorage.setItem('respuesta_' + input.dataset.idpregunta, input.value);
                    }
                });

                // Para selects, también escuchar el evento 'change'
                if (input.tagName === 'SELECT') {
                    input.addEventListener('change', function() {
                        localStorage.setItem('respuesta_' + input.dataset.idpregunta, input.value);
                    });
                }
            });

            // Eliminar los valores de localStorage cuando se envía el formulario
            form.addEventListener('submit', function() {
                inputs.forEach(function(input) {
                    localStorage.removeItem('respuesta_' + input.dataset.idpregunta); // Eliminar solo los valores específicos
                });
            });
        });
        //////////////////////PAISES OTROS TEXTO PLANO //////////////////






        ////////////////////////////////////////////////////////////////
        /////////COMBO DINAMICO DE ESTADOS MUNICIPIOS///////////////////////
        let estadoOriginal = '';
        let municipioOriginal = '';

        function cargarEstados(selectPais, idPregunta) {
            const paisId = selectPais.value; // ID del país seleccionado
            const estadoIdPregunta = idPregunta + 1; // El siguiente select es el de estados
            const estadoSelect = document.getElementById("estado_" + estadoIdPregunta);
            const municipioIdPregunta = estadoIdPregunta + 1; // El siguiente select es el de municipios
            const municipioSelect = document.getElementById("municipio_" + municipioIdPregunta);

            // Guardar HTML original si aún no está guardado
            if (!estadoOriginal) estadoOriginal = estadoSelect.outerHTML;
            if (!municipioOriginal) municipioOriginal = municipioSelect.outerHTML;
            console.log("Buscando estado con ID:", "estado_" + estadoIdPregunta); // Verifica que esté buscando el estado correcto

            console.log("pais:  " + paisId);
            if (estadoSelect) {
                // Si el país seleccionado es "Otro"
                if (paisId === "otro") {
                    // Cambiar los selects a inputs de texto
                    estadoSelect.outerHTML = `<input type='text' id='estado_${estadoIdPregunta}' name='respuestas[${estadoIdPregunta}]' placeholder='Especifica tu estado'  />`;
                    municipioSelect.outerHTML = `<input type='text' id='municipio_${municipioIdPregunta}' name='respuestas[${municipioIdPregunta}]' placeholder='Especifica tu municipio' required />`;
                } else {
                    // Restaurar los selects originales si se selecciona un país de la base de datos
                    estadoSelect.outerHTML = estadoOriginal;
                    municipioSelect.outerHTML = municipioOriginal;

                    // Refresca los elementos de select
                    const estadoSelectUpdated = document.getElementById("estado_" + estadoIdPregunta);
                    const municipioSelectUpdated = document.getElementById("municipio_" + municipioIdPregunta);

                    // Ahora se carga el select de estados
                    if (paisId) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'cargar_datos.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                estadoSelectUpdated.innerHTML = xhr.responseText; // Actualiza el select de estados
                            }
                        };
                        xhr.send('accion=estados&pais_id=' + paisId); // Envía el ID del país seleccionado
                    } else {
                        estadoSelectUpdated.innerHTML = "<option value=''>Selecciona un estado</option>"; // Si no hay país, restablece el select
                    }
                }
            } else {
                console.error("El elemento con ID 'estado_" + estadoIdPregunta + "' no existe en el DOM.");
            }
        }



        function cargarMunicipios(selectEstado, idPregunta) {
            const estadoId = selectEstado.value;

            // Calcula el ID del municipio basado en el ID de la pregunta
            const municipioIdPregunta = idPregunta + 1; // Ajusta según sea necesario

            console.log("Estado seleccionado:", estadoId);
            console.log("Buscando municipio con ID:", "municipio_" + municipioIdPregunta); // Para depuración

            if (estadoId) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'cargar_datos.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const municipioSelect = document.getElementById("municipio_" + municipioIdPregunta); // Utiliza el nuevo ID calculado

                        if (municipioSelect) {
                            municipioSelect.innerHTML = xhr.responseText; // Rellena los municipios
                        } else {
                            console.error("El elemento con ID 'municipio_" + municipioIdPregunta + "' no existe en el DOM.");
                        }
                    }
                };

                xhr.send('accion=municipios&estado_id=' + estadoId);
            } else {
                const municipioSelect = document.getElementById("municipio_" + municipioIdPregunta);
                if (municipioSelect) {
                    municipioSelect.innerHTML = "<option value=''>Selecciona un municipio</option>";
                } else {
                    console.error("El elemento con ID 'municipio_" + municipioIdPregunta + "' no existe en el DOM.");
                }
            }
        }



// Obtener todos los radio buttons generados dinámicamente
// Variable para almacenar el último radio seleccionado
let lastChecked = null;

// Obtener todos los radio buttons generados dinámicamente
const radios = document.querySelectorAll('input[type="radio"][name^="respuestas"]');

radios.forEach(radio => {
    radio.addEventListener('click', function() {
        // Si el radio ya estaba seleccionado, deseleccionarlo
        if (this === lastChecked) {
            this.checked = false;
            lastChecked = null; // Reiniciar la variable
        } else {
            // Si no estaba seleccionado, actualizar la variable
            lastChecked = this;
        }
    });
});



document.getElementById('encuestaForm').addEventListener('submit', function(event) {
    var form = event.target;
    var alertContainer = document.getElementById('alert-container');
    var valid = true;
    var errorMessages = [];

    // Limpiar el contenedor de alertas
    alertContainer.innerHTML = '';
    
    // Eliminar mensajes de error previos
    var errorElements = form.querySelectorAll('.error-message');
    errorElements.forEach(function(el) {
        el.remove();
    });

    // Verificar todos los campos requeridos
    var requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(function(input) {
        // Verificar si está vacío o no es válido
        if (input.type === 'radio') {
            // Verificar si algún radio con el mismo 'name' está seleccionado
            var name = input.name;
            if (!form.querySelector('input[name="' + name + '"]:checked')) {
                valid = false;
                var idPregunta = input.getAttribute('data-idpregunta') || 'sin ID';
                var errorMessage = 'Pregunta #' + idPregunta + ': Debe seleccionar una opción.';
                errorMessages.push(errorMessage);

                // Agregar mensaje de error a la pregunta (en lugar de al input individual)
                var parentTd = input.closest('td'); // Encuentra el 'td' que contiene el radio
                var errorElement = createErrorElement(errorMessage);
                parentTd.appendChild(errorElement);
            }
        } else if (input.value.trim() === '') {
            // Otros campos como input text, select, textarea, etc.
            valid = false;
            var idPregunta = input.getAttribute('data-idpregunta') || 'sin ID';
            var errorMessage = 'Pregunta #' + idPregunta + ': Este campo es requerido.';
            errorMessages.push(errorMessage);

            // Agregar mensaje de error al campo
            var errorElement = createErrorElement(errorMessage);
            input.parentElement.appendChild(errorElement);
        }
    });

    // Validación personalizada para otros campos (ejemplo de CURP)
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

    // Si hay errores, mostrar alerta y detener el envío del formulario
    if (!valid) {
        var alert = createAlert('Hay errores en el formulario.');
        alertContainer.appendChild(alert);
        event.preventDefault(); // Detener el envío del formulario
    }
});
    </script>

</body>

</html>