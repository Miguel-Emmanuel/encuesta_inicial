<?php
session_start();
require_once('../../../../database/conexion.php');
require('../../../../PDF/fpdf.php');
try {
    // Código de generación de PDF


    // Asegurar que MySQL use UTF-8
    $conexion->set_charset("utf8");

    // Configurar la codificación global
    mb_internal_encoding("UTF-8");
    mb_http_output("ISO-8859-1");

    // Iniciar el buffer de salida
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estudiante'])) {
        $estudiante_id = intval($_POST['estudiante']);

        $query = $conexion->prepare("
SELECT 
    e.matricula, 
    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS nombre_completo,
    g.nomenclatura AS grupo, 
    u.email,
    p.id AS numero_pregunta,
    p.pregunta,
    s.descripcion AS seccion_nombre,
    r.respuesta,
    COUNT(p.id) OVER (PARTITION BY s.id) AS total_preguntas_seccion,
    ROW_NUMBER() OVER (PARTITION BY s.id ORDER BY p.id) AS numero_pregunta_seccion,
    MIN(p.id) OVER (PARTITION BY s.id) AS primera_pregunta,
    MAX(p.id) OVER (PARTITION BY s.id) AS ultima_pregunta
FROM estudiantes AS e
INNER JOIN usuarios AS u ON e.usuario_id = u.id
INNER JOIN estudiante_grupo AS eg ON eg.estudiante_id = e.id
INNER JOIN t_grupos AS g ON g.id = eg.grupo_id
LEFT JOIN preguntas AS p ON p.seccion_id IN (SELECT id FROM secciones)
LEFT JOIN secciones AS s ON s.id = p.seccion_id
LEFT JOIN respuestas AS r ON r.pregunta_id = p.id AND r.estudiante_id = e.id  -- 🔥 Se asegura que la respuesta sea solo del estudiante correcto
WHERE e.id = ?
ORDER BY s.id ASC, p.id ASC;

");


        $query->bind_param("i", $estudiante_id);
        $query->execute();
        $resultado = $query->get_result();

        if ($resultado->num_rows > 0) {
            // Crear PDF



            class PDF extends FPDF
            {
                // Encabezado (necesario para inicializar el alias de páginas)
                function Header()
                {
                    $this->AliasNbPages(); // Inicializa el alias {nb}
                  // Ubicar la primera imagen en el lado izquierdo (coordenada x = 10)
// Ubicar la primera imagen en el lado izquierdo (coordenada x = 10)
// $this->Image('C:\xampp\htdocs\EncuestaInicial\public\img\gobierno.png', 20, 16, 40); // (archivo, x, y, ancho)
// $this->Ln(5);


///////////////Ruta para servidor////////////////

$imgPath2 = realpath(__DIR__ . '/../../../../public/img/gobierno.png');
if (!$imgPath2) {
    die("No se encontró la imagen en la ruta: " . __DIR__ . '/../../../../public/img/gobierno.png');
}
$this->Image($imgPath2, 20, 16, 40);
$this->Ln(5);

////////////////////////////////////////

// Ubicar la segunda imagen en el lado derecho (coordenada x = ancho de la página - ancho de la imagen)
$pageWidth = $this->GetPageWidth(); // Obtener el ancho total de la página
$imageWidth = 40; // El ancho de la imagen

// Calcula la posición x para la segunda imagen en el lado derecho
$rightX = $pageWidth - $imageWidth - 10; // 10 es el margen desde el borde derecho
$this->Image('C:\xampp\htdocs\EncuestaInicial\public\img\Logo_UTVT.jpg', $rightX, 8, 40); // (archivo, x, y, ancho)                   


///////////////Ruta para servidor////////////////

// $imgPath = realpath(__DIR__ . '/../../../../public/img/Logo_UTVT.jpg');
// if (!$imgPath) {
//     die("No se encontró la imagen en la ruta: " . __DIR__ . '/../../../../public/img/Logo_UTVT.jpg');
// }
// $this->Image($imgPath, $rightX, 8, 40); // (archivo, x, y, ancho)  

////////////////////////////////////////

//Configurar fuente para el título
                    $this->SetFont('Arial', 'B', 12);
                    $this->Cell(0, 10, '', 0, 1, 'C'); // Texto centrado
                    $this->Ln(9); // Espacio después del título



                }

                // Pie de página con el formato "Página X - Y"
                function Footer()
                {
                    $this->SetY(-15); // Posiciona el pie de página a 15 mm del borde inferior
                    $this->SetFont('Arial', 'I', 10); // Fuente Arial, cursiva, tamaño 10
                    $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . ' - {nb}', 0, 0, 'C'); // Página X - Y
                }
            }

            // Crear PDF con la nueva clase
            $pdf = new PDF();
            $pdf->AliasNbPages(); // Permite que {nb} se reemplace con el total de páginas
            $pdf->SetFont('Arial', '', 12);


            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(0, 10, 'Reporte de Estudiante', 0, 1, 'C');
            $pdf->Ln(5);

            // Obtener la primera fila para la información del estudiante
            $primera_fila = $resultado->fetch_assoc();

            // Información del estudiante
            // $pdf->SetFont('Arial', '', 12);
            // $pdf->Cell(0, 10, mb_convert_encoding('Matrícula: ' . $primera_fila['matricula'], 'windows-1252', 'UTF-8'), 0, 1);
            // $pdf->Cell(0, 10, mb_convert_encoding('Nombre: ' . $primera_fila['nombre_completo'], 'windows-1252', 'UTF-8'), 0, 1);
            // $pdf->Cell(0, 10, mb_convert_encoding('Grupo: ' . $primera_fila['grupo'], 'windows-1252', 'UTF-8'), 0, 1);
            // $pdf->Cell(0, 10, mb_convert_encoding('Correo: ' . $primera_fila['email'], 'windows-1252', 'UTF-8'), 0, 1);



            $pdf->SetFont('Arial', 'B', 12); // Fuente para los encabezados de la tabla

            // Establecer color de fondo para las celdas de encabezado
            $pdf->SetFillColor(48, 125, 105, 255); // Color gris claro para el fondo de la cabecera

            // Cabecera de la tabla con los encabezados
            $pdf->Cell(60, 6, 'Nombre', 1, 0, 'C', true);
            $pdf->Cell(0, 6, mb_convert_encoding($primera_fila['nombre_completo'], 'windows-1252', 'UTF-8'), 1, 1, 'C', false);

            // Nombre
            $pdf->Cell(60, 6, 'Matricula', 1, 0, 'C', true);
            $pdf->Cell(0, 6, mb_convert_encoding($primera_fila['matricula'], 'windows-1252', 'UTF-8'), 1, 1, 'C', false);

            // Grupo
            $pdf->Cell(60, 6, 'Grupo', 1, 0, 'C', true);
            $pdf->Cell(0, 6, mb_convert_encoding($primera_fila['grupo'], 'windows-1252', 'UTF-8'), 1, 1, 'C', false);

            // Correo
            $pdf->Cell(60, 6, 'Correo', 1, 0, 'C', true);
            $pdf->Cell(0, 6, mb_convert_encoding($primera_fila['email'], 'windows-1252', 'UTF-8'), 1, 1, 'C', false);

            $pdf->Ln(4);

            // Reiniciar el puntero de resultados
            $resultado->data_seek(0);

            // Variables para detectar cambios de sección
            $seccion_actual = null;



// Inicialización de variables de columna
$ancho_columna_pregunta = 95; // Ancho de la columna para la pregunta (izquierda)
$ancho_columna_respuesta = 95; // Ancho de la columna para la respuesta (derecha)
$alto_fila = 4; // Altura de las filas

while ($fila = $resultado->fetch_assoc()) {
    // Si cambia la sección, imprimir un título de sección
    if ($fila['seccion_nombre'] !== $seccion_actual) {
        $seccion_actual = $fila['seccion_nombre'];
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(0, 10, mb_convert_encoding('Sección: ' . $seccion_actual, 'windows-1252', 'UTF-8'), 0, 1);
        
        // Imprimir cantidad de preguntas y rango en la sección
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, mb_convert_encoding(
            $fila['total_preguntas_seccion'] . ' (' . $fila['primera_pregunta'] . '-' . $fila['ultima_pregunta'] . ')',
            'windows-1252', 'UTF-8'
        ), 0, 1);
        
        $pdf->Ln(0);
        $pdf->SetFont('Arial', '', 11);
    }

    // Imprimir la pregunta y respuesta en dos columnas
    if ($fila['pregunta'] !== null) {
        // Verificar si hay suficiente espacio en la página para la pregunta y respuesta
        if ($pdf->GetY() + $alto_fila > 270) { // Si el espacio restante es menor que 10 mm
            $pdf->AddPage(); // Si no hay suficiente espacio, agregar una nueva página
        }

        // Imprimir la pregunta en la columna izquierda (pregunta)
        $pdf->SetFont('Arial', 'B', 10);
        $pregunta = mb_convert_encoding($fila['pregunta'], 'ISO-8859-1', 'UTF-8');
        $pdf->SetX(10); // Establecer la posición en X para la columna de preguntas
        $pdf->MultiCell($ancho_columna_pregunta, $alto_fila, $pregunta, 1, 'L', false);  
        // Establecer el color de fondo gris claro para las respuestas
        $pdf->SetFillColor(230, 230, 230); // Gris claro (RGB: 230, 230, 230)

        // Imprimir la respuesta en la columna derecha (respuesta)
        $pdf->SetFont('Arial', 'I', 8);
        $respuesta = mb_convert_encoding($fila['respuesta'] ?: 'No respondida', 'ISO-8859-1', 'UTF-8');
        $pdf->SetXY(10 + $ancho_columna_pregunta, $pdf->GetY() - $alto_fila); // Ajustar Y a la misma línea
        $pdf->MultiCell($ancho_columna_respuesta, $alto_fila, $respuesta, 1, 'L', true);

        // Asegurarse de que la próxima pregunta/respuesta no se superponga
        $pdf->Ln(); // Espacio extra entre filas
    }

    // Verificar si se ha alcanzado el final de la página y agregar una nueva página si es necesario
    if ($pdf->GetY() > 270) {
        $pdf->AddPage();
    }
}


// Limpiar buffer antes de generar el PDF
            ob_end_clean();

            $pdf->Output('I', 'Reporte_Estudiante.pdf'); // Mostrar en el navegador

            // Descargar el archivo PDF
            // $pdf->Output('D', 'Reporte_Estudiante_' . $primera_fila['matricula'] . '.pdf');
        } else {
            echo "No se encontró información para el estudiante seleccionado.";
        }
    } else {
        echo "No se recibió la información necesaria.";
    }
} catch (Exception $e) {
    echo 'Error al generar el PDF: ' . $e->getMessage();
}
