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
        
        

        class PDF extends FPDF {
            // Encabezado (necesario para inicializar el alias de páginas)
            function Header() {
                $this->AliasNbPages(); // Inicializa el alias {nb}
                                //Agregar imagen (ajusta la ruta, posición y tamaño según sea necesario)
                                // $this->Image(__DIR__ . '/../../public/img/Logo_UTVT.jpg', 10, 8, 40);// (archivo, x, y, ancho)
                                $this->Image(__DIR__ . '/../../../../public/img/Logo_UTVT.jpg', 10, 8, 40);

                                //Configurar fuente para el título
                                $this->SetFont('Arial', 'B', 12);
                                $this->Cell(0, 10, '', 0, 1, 'C'); // Texto centrado
                                $this->Ln(10); // Espacio después del título
                
            }
        
            // Pie de página con el formato "Página X - Y"
            function Footer() {
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
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Cell(0, 10, 'Reporte de Estudiante', 0, 1, 'C');
        $pdf->Ln(10);

        // Obtener la primera fila para la información del estudiante
        $primera_fila = $resultado->fetch_assoc();

        // Información del estudiante
        // $pdf->SetFont('Arial', '', 12);
        // $pdf->Cell(0, 10, mb_convert_encoding('Matrícula: ' . $primera_fila['matricula'], 'windows-1252', 'UTF-8'), 0, 1);
        // $pdf->Cell(0, 10, mb_convert_encoding('Nombre: ' . $primera_fila['nombre_completo'], 'windows-1252', 'UTF-8'), 0, 1);
        // $pdf->Cell(0, 10, mb_convert_encoding('Grupo: ' . $primera_fila['grupo'], 'windows-1252', 'UTF-8'), 0, 1);
        // $pdf->Cell(0, 10, mb_convert_encoding('Correo: ' . $primera_fila['email'], 'windows-1252', 'UTF-8'), 0, 1);
        $pdf->SetFont('Arial', 'B', 12); // Texto estático en negrita
$pdf->Cell(30, 10, mb_convert_encoding('Matrícula: ', 'windows-1252', 'UTF-8'), 0, 0);
$pdf->SetFont('Arial', 'I', 12); // Datos en texto normal
$pdf->Cell(0, 10, mb_convert_encoding($primera_fila['matricula'], 'windows-1252', 'UTF-8'), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, mb_convert_encoding('Nombre: ', 'windows-1252', 'UTF-8'), 0, 0);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, mb_convert_encoding($primera_fila['nombre_completo'], 'windows-1252', 'UTF-8'), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, mb_convert_encoding('Grupo: ', 'windows-1252', 'UTF-8'), 0, 0);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, mb_convert_encoding($primera_fila['grupo'], 'windows-1252', 'UTF-8'), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, mb_convert_encoding('Correo: ', 'windows-1252', 'UTF-8'), 0, 0);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, mb_convert_encoding($primera_fila['email'], 'windows-1252', 'UTF-8'), 0, 1);

                $pdf->Ln(5);

        // Reiniciar el puntero de resultados
        $resultado->data_seek(0);

        // Variables para detectar cambios de sección
        $seccion_actual = null;

        // Recorrer preguntas y respuestas
        while ($fila = $resultado->fetch_assoc()) {
            // Si cambia la sección, imprimir un título de sección
            if ($fila['seccion_nombre'] !== $seccion_actual) {
                $seccion_actual = $fila['seccion_nombre'];
                $pdf->SetFont('Arial', 'B', 20);
                // $pdf->Ln(5);
                $pdf->Cell(0, 10, mb_convert_encoding('Sección: ' . $seccion_actual, 'windows-1252', 'UTF-8'), 0, 1);
                
                // Imprimir cantidad de preguntas y rango en la sección
                $pdf->SetFont('Arial', 'I', 12);
                $pdf->Cell(0, 10, mb_convert_encoding(
                    $fila['total_preguntas_seccion'] . ' (' . $fila['primera_pregunta'] . '-' . $fila['ultima_pregunta'] . ')',
                    'windows-1252', 'UTF-8'
                ), 0, 1);
                
                $pdf->Ln(3);
                $pdf->SetFont('Arial', '', 11);
            }
            
            
            // Imprimir la pregunta
            if ($fila['pregunta'] !== null) {
                // $pdf->SetFont('Arial', 'BI', 15);
                // $pdf->Cell(38, 10, mb_convert_encoding('Pregunta ('   . $fila['numero_pregunta'] . '): ', 'windows-1252', 'UTF-8'), 0, 0);
                
                // Guardar la posición inicial de la pregunta
                $posY = $pdf->GetY();
                
                // Pregunta en varias líneas
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->MultiCell(60, 3, mb_convert_encoding($fila['pregunta'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
                
                // Guardar la nueva posición después de la pregunta
                $alturaPregunta = $pdf->GetY() - $posY; // Altura total ocupada por la pregunta
                
                // Verificar si hay suficiente espacio en la página para la respuesta
                // if ($pdf->GetY() + 10 > 270) { // Comprobamos si el espacio restante es menor que 10 mm
                //     $pdf->AddPage(); // Si no hay suficiente espacio, agregar una nueva página
                // }
                
                // Volver a la posición original para alinear la respuesta arriba
                $pdf->SetY($posY);
                $pdf->SetX(70); // Mover la respuesta a la derecha
                
                // Imprimir la respuesta
                $pdf->SetFont('Arial', 'I', 8);
                $pdf->MultiCell(120, 3, mb_convert_encoding($fila['respuesta'] ?: 'No respondida', 'ISO-8859-1', 'UTF-8'), 0, 'L');
                
                // Asegurar que la próxima pregunta no se encime
                $pdf->SetY($posY + $alturaPregunta); // Ajusta la posición según la altura de la pregunta
                $pdf->Ln(5); // Espacio extra entre filas
                
                // Verificar si se ha alcanzado el final de la página
                if ($pdf->GetY() > 270) {
                    $pdf->AddPage();
                }
                

                

                // $pdf->SetFont('Arial', 'B', 13);
                // $pdf->MultiCell(0, 8, 'Pregunta ('   . $fila['numero_pregunta'] . '): ' . mb_convert_encoding($fila['pregunta'], 'ISO-8859-1', 'UTF-8'), 0);
                
           
                // $pdf->SetFont('Arial', 'B', 12);
                // $pdf->Cell(24, 10, mb_convert_encoding('Respuesta: ', 'windows-1252', 'UTF-8'), 0, 0);
                // $pdf->SetFont('Arial', 'I', 10);
                // $pdf->Cell(0, 10,  mb_convert_encoding($fila['respuesta'] ?: 'No respondida', 'ISO-8859-1', 'UTF-8'), 0, 1);
                
           
                // $pdf->Ln(3);
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
?>