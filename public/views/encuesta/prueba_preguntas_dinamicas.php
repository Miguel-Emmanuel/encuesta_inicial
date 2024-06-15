<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Dinámica</title>
    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluir tu script personalizado -->
    <script>
    function obtenerValor(opcion, preguntaId) {
        console.log('El valor del radio button es: ' + opcion);
        console.log('Valor de preguntaId:', preguntaId);

        if (opcion === 'Si') {
            $('#dependientes-' + preguntaId).slideDown();
        } else {
            $('#dependientes-' + preguntaId).slideUp();
        }
    }
</script>
</head>
<body>
    <!-- Contenido de tu página -->
    <div class="container">
        <!-- Ejemplo de preguntas y respuestas obtenidas de la base de datos -->
        <?php
        require("../../../database/conexion.php");

        // Configuración de conexión a la base de datos (ajusta según tus credenciales)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "encuesta001";

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("La conexión ha fallado: " . $conn->connect_error);
        }

        // Consulta SQL para obtener todas las preguntas principales
        $sql = "SELECT p.id, p.pregunta, p.tipo
                FROM preguntas p
                WHERE NOT EXISTS (SELECT 1 FROM dependencias_preguntas dp WHERE dp.pregunta_id = p.id)";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar las preguntas principales y sus dependientes
            while ($row = $result->fetch_assoc()) {
                $preguntaId = $row['id'];
                $preguntaTexto = $row['pregunta'];
                $tipoPregunta = $row['tipo'];

                echo "<div class='pregunta' data-pregunta-id='$preguntaId'>";
                echo "<p class='pregunta-texto'>$preguntaId. <b>$preguntaTexto</b></p>";

                switch ($tipoPregunta) {
                    case 'texto':
                        echo "<input type='text' name='respuestas[$preguntaId]' class='respuesta-input' data-idpregunta='$preguntaId' placeholder='Respuesta para la pregunta'>";
                        break;
                    case 'fecha':
                        echo "<input type='date' name='respuestas[$preguntaId]' class='respuesta-fecha' data-idpregunta='$preguntaId'>";
                        break;
                    case 'correo':
                        echo "<input type='email' name='respuestas[$preguntaId]' class='respuesta-correo' data-idpregunta='$preguntaId' required>";
                        break;
                    case 'curp':
                        echo "<input type='text' name='respuestas[$preguntaId]' class='respuesta-curp' data-idpregunta='$preguntaId'  required>";
                        break;
                    case 'rfc':
                        echo "<input type='text' name='respuestas[$preguntaId]' class='respuesta-rfc' data-idpregunta='$preguntaId' required>";
                        break;
                    case 'numero':
                        echo "<input type='number' name='respuestas[$preguntaId]' class='respuesta-numero' data-idpregunta='$preguntaId' pattern='\d+' required>";
                        break;
                    case 'r_social':
                        echo "<textarea name='respuestas[$preguntaId]' class='respuesta-r_social' data-idpregunta='$preguntaId' required></textarea>";
                        break;
                    case 'c_postal':
                        echo "<input type='text' name='respuestas[$preguntaId]' class='respuesta-c_postal' data-idpregunta='$preguntaId' required>";
                        break;
                    case 'texto_a':
                        echo "<textarea name='respuestas[$preguntaId]' class='respuesta-texto_a' data-idpregunta='$preguntaId' required></textarea>";
                        break;
                    case 'opcion':
                        $opciones_respuesta = $conn->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $preguntaId");
                        if ($opciones_respuesta->num_rows > 0) {
                            echo "<div class='pregunta'>";
                            echo "<p class='pregunta-texto'>Opciones para pregunta ID: $preguntaId</p>";
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
                                        echo "<td><input type='radio' class='$preguntaId' name='respuestas[$preguntaId]' value='$opcion2' onclick='obtenerValor(\"$opcion1\", $preguntaId)'></td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $preguntaId</p>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $preguntaId</p>";
                        }
                        break;
                    case 'select':
                        $opciones_respuesta = $conn->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $preguntaId");
                        if ($opciones_respuesta->num_rows > 0) {
                            echo "<select name='respuestas[$preguntaId]' class='respuesta-select'>";
                            while ($opcion = $opciones_respuesta->fetch_object()) {
                                $opcionId = $opcion->id;
                                $nombreOpcion = $opcion->opcion1;
                                echo "<option value='$opcionId'>$nombreOpcion</option>";
                            }
                            echo "</select>";
                        } else {
                            echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $preguntaId</p>";
                        }
                        break;
                    case 'multi':
                        $opciones_respuesta = $conn->query("SELECT * FROM opciones_respuesta WHERE pregunta_id = $preguntaId");
                        if ($opciones_respuesta->num_rows > 0) {
                            echo "<div class='pregunta'>";
                            echo "<p class='pregunta-texto'>Opciones para pregunta ID: $preguntaId</p>";
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
                                        echo "<td><input type='radio' name='respuestas[$preguntaId][$opcion1]' value='$opcion2'></td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $preguntaId</p>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p class='pregunta-texto'>No se encontraron opciones para la pregunta ID: $preguntaId</p>";
                        }
                        break;
                    default:
                        echo "Tipo de pregunta no soportado";
                        break;
                }

                echo "<div class='preguntas-dependientes' id='dependientes-$preguntaId' style='display:none;'>";
                echo "<p>Preguntas dependientes...</p>";

                // Consulta para obtener las preguntas dependientes de la pregunta actual
                $sqlDependientes = "SELECT pd.id, pd.pregunta
                                    FROM preguntas pd
                                    JOIN dependencias_preguntas dp ON pd.id = dp.pregunta_id
                                    WHERE dp.depende_de_pregunta_id = $preguntaId";

                $resultDependientes = $conn->query($sqlDependientes);

                if ($resultDependientes->num_rows > 0) {
                    // Mostrar las preguntas dependientes encontradas
                    while ($rowDependiente = $resultDependientes->fetch_assoc()) {
                        $idPreguntaDependiente = $rowDependiente['id'];
                        $textoPreguntaDependiente = $rowDependiente['pregunta'];

                        echo "<div class='pregunta dependiente' data-pregunta-id='$idPreguntaDependiente'>";
                        echo "<p class='pregunta-texto'>$idPreguntaDependiente. <b>$textoPreguntaDependiente</b></p>";
                        echo "<input type='text' name='respuesta_$idPreguntaDependiente' class='respuesta-texto'>";
                        echo "</div>";
                    }
                } else {
                    echo "No hay preguntas dependientes para esta pregunta.";
                }

                echo "</div>"; // Cierre de preguntas-dependientes
                echo "</div>"; // Cierre de pregunta principal
            }
        } else {
            echo "No se encontraron preguntas.";
        }

        // Cerrar conexión
        $conn->close();
        ?>
    </div>
</body>
</html>
