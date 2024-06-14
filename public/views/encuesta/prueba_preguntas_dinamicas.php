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
        $(document).ready(function() {
            // Evento de cambio para cualquier pregunta con dependencias
            $('select.respuesta-select').on('change', function() {
                var respuesta = $(this).val();
                var preguntaId = $(this).closest('.pregunta').data('pregunta-id');
                
                // Mostrar u ocultar preguntas dependientes según la respuesta seleccionada
                if (respuesta === 'si') {
                    $('#dependientes-' + preguntaId).slideDown();
                } else {
                    $('#dependientes-' + preguntaId).slideUp();
                }
            });
        });
    </script>
</head>
<body>
    <!-- Contenido de tu página -->
    <div class="container">
        <!-- Ejemplo de preguntas y respuestas obtenidas de la base de datos -->
        <?php
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
        $sql = "SELECT p.id, p.pregunta
                FROM preguntas p
                WHERE NOT EXISTS (SELECT 1 FROM dependencias_preguntas dp WHERE dp.pregunta_id = p.id)";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar las preguntas principales y sus dependientes
            while ($row = $result->fetch_assoc()) {
                $preguntaId = $row['id'];
                $preguntaTexto = $row['pregunta'];

                echo "<div class='pregunta' data-pregunta-id='$preguntaId'>";
                echo "<p class='pregunta-texto'>$preguntaId. $preguntaTexto</p>";
                echo "<select name='respuesta_$preguntaId' class='respuesta-select'>";
                echo "<option value=''>Seleccione...</option>";
                echo "<option value='si'>Sí</option>";
                echo "<option value='no'>No</option>";
                echo "</select>";
                echo "</div>";

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
