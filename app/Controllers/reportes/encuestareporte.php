<?php
session_start();
require_once('../../../database/conexion.php');  
require('../../../PDF/fpdf.php');  

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
            p.pregunta,
            r.respuesta
        FROM estudiantes AS e
        INNER JOIN usuarios AS u ON e.usuario_id = u.id
        INNER JOIN estudiante_grupo AS eg ON eg.estudiante_id = e.id
        INNER JOIN t_grupos AS g ON g.id = eg.grupo_id
        LEFT JOIN respuestas AS r ON r.estudiante_id = e.id
        LEFT JOIN preguntas AS p ON p.id = r.pregunta_id
        WHERE e.id = ?;
    ");
    
    $query->bind_param("i", $estudiante_id);
    $query->execute();
    $resultado = $query->get_result();
    
    if ($resultado->num_rows > 0) {
        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Reporte de Estudiante', 0, 1, 'C');
        $pdf->Ln(10);

        // Obtener la primera fila
        $primera_fila = $resultado->fetch_assoc();

        // Información del estudiante
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Matricula: ' . mb_convert_encoding($primera_fila['matricula'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, 'Nombre: ' . mb_convert_encoding($primera_fila['nombre_completo'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, 'Grupo: ' . mb_convert_encoding($primera_fila['grupo'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 10, 'Correo: ' . mb_convert_encoding($primera_fila['email'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(5);

        // Encabezado de preguntas y respuestas
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Preguntas y Respuestas:', 0, 1);
        $pdf->Ln(3);

        // Volver a recorrer el resultado
        $pdf->SetFont('Arial', '', 11);
        $resultado->data_seek(0);

        while ($fila = $resultado->fetch_assoc()) {
            if ($fila['pregunta'] !== null) {
                $pdf->MultiCell(0, 8, 'Pregunta: ' . mb_convert_encoding($fila['pregunta'], 'ISO-8859-1', 'UTF-8'), 0);
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->MultiCell(0, 8, 'Respuesta: ' . mb_convert_encoding($fila['respuesta'] ?: 'No respondida', 'ISO-8859-1', 'UTF-8'), 0);
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 11);
            }
        }

        // Limpiar buffer antes de generar el PDF
        ob_end_clean();
        $pdf->Output('I', 'Reporte_Estudiante.pdf'); // Mostrar en el navegador
    } else {
        echo "No se encontró información para el estudiante seleccionado.";
    }
} else {
    echo "No se recibió la información necesaria.";
}
?>
