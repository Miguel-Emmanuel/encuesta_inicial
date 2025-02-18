<?php
session_start();
require_once('../../../../database/conexion.php');  
require_once('../../../../vendor/autoload.php'); // Asegúrate de la ruta correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    $conexion->set_charset("utf8");

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
            LEFT JOIN respuestas AS r ON r.pregunta_id = p.id AND r.estudiante_id = e.id
            WHERE e.id = ?
            ORDER BY s.id ASC, p.id ASC;
        ");
        $query->bind_param("i", $estudiante_id);
        $query->execute();
        $resultado = $query->get_result();

        if ($resultado->num_rows > 0) {
            $primera_fila = $resultado->fetch_assoc();
            $resultado->data_seek(0);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Título
            $sheet->setCellValue('A1', 'Reporte de Estudiante');
            $sheet->mergeCells('A1:L1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Datos del estudiante
            $sheet->setCellValue('A2', 'Matrícula:');
            $sheet->setCellValue('B2', $primera_fila['matricula']);
            $sheet->setCellValue('A3', 'Nombre:');
            $sheet->setCellValue('B3', $primera_fila['nombre_completo']);
            $sheet->setCellValue('A4', 'Grupo:');
            $sheet->setCellValue('B4', $primera_fila['grupo']);
            $sheet->setCellValue('A5', 'Correo:');
            $sheet->setCellValue('B5', $primera_fila['email']);

            // Encabezados para las preguntas/respuestas
            $sheet->setCellValue('A7', 'Sección');
            $sheet->setCellValue('B7', 'Número Pregunta');
            $sheet->setCellValue('C7', 'Pregunta');
            $sheet->setCellValue('D7', 'Respuesta');
            $sheet->setCellValue('E7', 'Total Preguntas Sección');
            $sheet->setCellValue('F7', 'Num. Pregunta Sección');
            $sheet->setCellValue('G7', 'Primera Pregunta Sección');
            $sheet->setCellValue('H7', 'Última Pregunta Sección');

            $fila_excel = 8;
            while ($row = $resultado->fetch_assoc()) {
                if ($row['pregunta'] !== null) {
                    $sheet->setCellValue('A' . $fila_excel, $row['seccion_nombre']);
                    $sheet->setCellValue('B' . $fila_excel, $row['numero_pregunta']);
                    $sheet->setCellValue('C' . $fila_excel, $row['pregunta']);
                    $sheet->setCellValue('D' . $fila_excel, $row['respuesta'] ?: 'No respondida');
                    $sheet->setCellValue('E' . $fila_excel, $row['total_preguntas_seccion']);
                    $sheet->setCellValue('F' . $fila_excel, $row['numero_pregunta_seccion']);
                    $sheet->setCellValue('G' . $fila_excel, $row['primera_pregunta']);
                    $sheet->setCellValue('H' . $fila_excel, $row['ultima_pregunta']);
                    $fila_excel++;
                }
            }

            foreach (range('A','H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Limpiar buffer antes de enviar encabezados
            if (ob_get_length()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_Estudiante.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } else {
            echo 'No se encontró información para el estudiante seleccionado.';
        }
    } else {
        echo 'No se recibió la información necesaria.';
    }
} catch (Exception $e) {
    echo 'Error al generar el Excel: ' . $e->getMessage();
}
